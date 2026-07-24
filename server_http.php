#!/usr/bin/env php
<?php
declare(strict_types=1);

/**
 * server_http.php
 * 生产环境专用 HTTP 启动入口（无 WebSocket / 无队列消费 / 无文件热重载）
 *
 * 设计目标：
 *  - 仅提供 HTTP 服务（http://0.0.0.0:8000），常驻多进程
 *  - 启动期预热 Schema（扫描 app/Modules）并冻结，避免运行期 DB 反射
 *  - 初始化 Redis / MySQL 连接池（每个 worker 进程独立持有）
 *  - 内存超限自动平滑重启，保障长时间稳定运行
 *  - 提供 /_health 健康检查端点
 *
 * 启动方式：
 *   php server_http.php start          # 前台（调试）
 *   php server_http.php start -d       # 后台守护（生产推荐）
 *   php server_http.php stop|restart|reload|status
 *
 * 路由缓存：
 *   config/app.php 的 'env' 设为 'prod' 后，Framework 会自动把扫描生成的
 *   RouteCollection 序列化到 storage/cache/routes.php，后续启动直接读取，
 *   无需每次扫描控制器 Attribute。请确保 storage/cache 目录可写。
 */

use Workerman\Worker;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request as WorkermanRequest;
use Workerman\Protocols\Http\Response as WorkermanResponse;
use Workerman\Timer;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Framework\Core\Framework;
use Framework\Schema\SchemaWarmup;
use Framework\Schema\SchemaRegistry;
use Framework\Utils\WorkermanHealth;
use Framework\Pool\RedisPool;
use Framework\Pool\MysqlPool;
use Framework\Pool\PoolManager;

// 只允许 CLI 模式运行
if (php_sapi_name() !== 'cli') {
    return;
}

define('WORKERMAN_ENV', true);
define('BASE_PATH', __DIR__);
define('APP_ROOT', __DIR__);
define('LOG_DIR', APP_ROOT . '/storage/workerman');
define('HEALTH_FILE', LOG_DIR . '/health.json');

// 创建日志目录
if (!is_dir(LOG_DIR)) {
    mkdir(LOG_DIR, 0777, true);
}

// -------------------------- 生产参数（按需调整） --------------------------
const HTTP_WORKER_COUNT   = 8;     // HTTP worker 进程数，建议 = CPU 核心数
const MEMORY_LIMIT_MB     = 512;   // 单 worker 内存上限（MB），超限平滑重启
const MEMORY_CHECK_INTERVAL = 10;  // 内存/健康检查周期（秒）

require_once __DIR__ . '/vendor/autoload.php';

// 生产环境关闭错误外显，仅记录到日志
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', LOG_DIR . '/php-error.log');

// 设置 Workerman 日志文件
Worker::$logFile = LOG_DIR . '/workerman.log';

// ----------------------------------------------------------------------
// 日志工具
// ----------------------------------------------------------------------
function log_info(string $msg): void {
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $msg . PHP_EOL;
    file_put_contents(LOG_DIR . '/server.log', $line, FILE_APPEND);
}

// ----------------------------------------------------------------------
// 健康检查与日志轮转
// ----------------------------------------------------------------------
function update_health(?Worker $worker = null): void {
    $snapshot = WorkermanHealth::snapshot($worker?->id, $worker?->name);
    WorkermanHealth::writeHealthFile(HEALTH_FILE, $snapshot);
    WorkermanHealth::appendMemoryHistory(LOG_DIR, $snapshot, $worker?->name ?? 'http');
}

function rotate_logs(): void {
    $files = [
        LOG_DIR . '/server.log',
        LOG_DIR . '/php-error.log',
    ];

    foreach ($files as $file) {
        if (file_exists($file) && filesize($file) > 2 * 1024 * 1024) {
            $new = LOG_DIR . '/' . basename($file, '.log') . '-' . date('Ymd_His') . '.log';
            rename($file, $new);
            log_info("[LogRotate] Rotated to $new");
        }
    }
}

// ----------------------------------------------------------------------
// Symfony Request / Response 转换
// ----------------------------------------------------------------------
function convert_to_workerman_response(SymfonyResponse $res): WorkermanResponse {
    $headers = [];

    foreach ($res->headers->allPreserveCase() as $name => $values) {
        if (strtolower($name) === 'set-cookie') {
            $headers[$name] = $values;
        } else {
            $headers[$name] = is_array($values) ? implode(', ', $values) : $values;
        }
    }

    $content = $res->getContent();

    // Workerman 会自动计算 Content-Length，避免重复头
    if (isset($headers['content-length'])) {
        unset($headers['content-length']);
    }

    return new WorkermanResponse($res->getStatusCode(), $headers, $content);
}

/**
 * 将 Workerman Request 转换为 Symfony Request（含上传文件解析）
 */
function convert_to_symfony_request(WorkermanRequest $request): SymfonyRequest
{
    $method = strtoupper($request->method());
    $uri = $request->uri();
    $rawBody = $request->rawBody();
    $remoteIp = $request->connection?->getRemoteIp() ?? '127.0.0.1';
    $remotePort = $request->connection?->getRemotePort() ?? 0;

    $uriParts = parse_url($uri);
    $pathInfo = $uriParts['path'] ?? '/';
    $queryString = $uriParts['query'] ?? '';

    $get = $request->get() ?? [];
    if (!empty($queryString)) {
        parse_str($queryString, $queryParams);
        $get = array_merge($queryParams, $get);
    }

    $post = $request->post() ?? [];
    $cookies = $request->cookie() ?? [];

    // 解析 Workerman 上传文件 -> Symfony UploadedFile
    $symfonyFiles = [];
    $wmFiles = $request->file() ?? [];

    foreach ($wmFiles as $field => $fileInfo) {
        // 单文件
        if (isset($fileInfo['tmp_name'])) {
            if (!empty($fileInfo['tmp_name']) && file_exists($fileInfo['tmp_name'])) {
                $symfonyFiles[$field] = new UploadedFile(
                    $fileInfo['tmp_name'],
                    $fileInfo['name'] ?? '',
                    $fileInfo['type'] ?? null,
                    $fileInfo['error'] ?? UPLOAD_ERR_OK,
                    true
                );
            }
            continue;
        }

        // 多文件
        if (is_array($fileInfo)) {
            $files = [];
            foreach ($fileInfo as $index => $item) {
                if (
                    !isset($item['tmp_name']) ||
                    empty($item['tmp_name']) ||
                    !file_exists($item['tmp_name'])
                ) {
                    continue;
                }
                $files[$index] = new UploadedFile(
                    $item['tmp_name'],
                    $item['name'] ?? '',
                    $item['type'] ?? null,
                    $item['error'] ?? UPLOAD_ERR_OK,
                    true
                );
            }
            if ($files) {
                $symfonyFiles[$field] = $files;
            }
        }
    }

    $headers = $request->header() ?? [];
    $parameters = array_merge($get, $post);

    $server = [
        'REQUEST_METHOD' => $method,
        'REQUEST_URI' => $uri,
        'PATH_INFO' => $pathInfo,
        'QUERY_STRING' => $queryString,
        'REMOTE_ADDR' => $remoteIp,
        'REMOTE_PORT' => $remotePort,
        'SERVER_PROTOCOL' => 'HTTP/1.1',
        'HTTP_HOST' => $headers['host'] ?? 'localhost',
        'CONTENT_LENGTH' => $headers['content-length'] ?? strlen($rawBody),
        'CONTENT_TYPE' => $headers['content-type'] ?? '',
        'PHP_SELF' => $pathInfo,
        'SCRIPT_NAME' => $pathInfo,
        'SCRIPT_FILENAME' => '',
    ];

    foreach ($headers as $name => $value) {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        $server[$key] = is_array($value) ? implode(', ', $value) : $value;
    }

    if (!isset($server['HTTP_X_FORWARDED_FOR'])) {
        $server['HTTP_X_FORWARDED_FOR'] = $remoteIp;
    }

    if (in_array($method, ['PUT', 'DELETE', 'PATCH']) && empty($post) && !empty($rawBody)) {
        parse_str($rawBody, $parsedPost);
        $post = array_merge($post, $parsedPost);
    }

    return new SymfonyRequest(
        $get,
        $post,
        [],
        $cookies,
        $symfonyFiles,
        $server,
        $rawBody
    );
}

/**
 * 获取文件的 MIME 类型
 */
function get_mime_type(string $filePath): string
{
    $mimeTypes = [
        'jpg'  => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png',
        'gif'  => 'image/gif', 'webp' => 'image/webp', 'svg' => 'image/svg+xml',
        'ico'  => 'image/x-icon', 'bmp' => 'image/bmp',
        'mp4'  => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg',
        'mp3'  => 'audio/mpeg', 'wav' => 'audio/wav',
        'pdf'  => 'application/pdf',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls'  => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'ppt'  => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'txt'  => 'text/plain', 'html' => 'text/html', 'css' => 'text/css',
        'js'   => 'application/javascript', 'json' => 'application/json', 'xml' => 'application/xml',
        'zip'  => 'application/zip', 'rar' => 'application/vnd.rar',
        '7z'   => 'application/x-7z-compressed', 'tar' => 'application/x-tar', 'gz' => 'application/gzip',
        'woff' => 'font/woff', 'woff2' => 'font/woff2', 'ttf' => 'font/ttf',
        'eot'  => 'application/vnd.ms-fontobject',
    ];

    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    return $mimeTypes[$extension] ?? 'application/octet-stream';
}

// ----------------------------------------------------------------------
// 创建 HTTP Worker
// ----------------------------------------------------------------------
$httpWorker = new Worker('http://0.0.0.0:8000');
$httpWorker->name = 'eqrayphp-http-prod';
$httpWorker->count = HTTP_WORKER_COUNT;

$framework = null;

// ----------------------------------------------------------------------
// HTTP Worker 启动回调
// ----------------------------------------------------------------------
$httpWorker->onWorkerStart = function(Worker $worker) use (&$framework) {
    log_info("[HTTP-Worker] PID " . getmypid() . " started");
    Worker::log("[HTTP-Worker] PID " . getmypid() . " started");
    update_health();

    // 初始化框架
    $framework = Framework::getInstance();

    // Schema 预热（对齐多模块架构：扫描 app/Modules）
    if (defined('WORKERMAN_ENV')) {
        SchemaWarmup::setScanPath(base_path('app/Modules'), 'App\\Modules');
        SchemaWarmup::warmupAll();
        SchemaRegistry::freeze();
    }

    // ---------------------------------------------------------------
    // 连接池初始化（每个 Worker 进程独立持有，不跨进程共享）
    // ---------------------------------------------------------------
    try {
        $redisConfig    = require BASE_PATH . '/config/redis.php';
        $databaseConfig = require BASE_PATH . '/config/database.php';

        // Redis 连接池
        if (!empty($redisConfig['pool']['enabled'])) {
            $primaryNode     = $redisConfig['nodes'][0] ?? [];
            $redisPoolConfig = array_merge($primaryNode, $redisConfig['pool']);
            PoolManager::register('redis.default', new RedisPool($redisPoolConfig));
            log_info(sprintf(
                '[HTTP-Worker #%d] Redis 连接池已初始化，空闲：%d / 最大：%d',
                $worker->id,
                $redisPoolConfig['min_connections'] ?? 2,
                $redisPoolConfig['max_connections'] ?? 10
            ));
        }

        // MySQL 连接池
        if (!empty($databaseConfig['pool']['enabled'])) {
            $mysqlConn       = $databaseConfig['connections']['mysql'] ?? [];
            $mysqlPoolConfig = array_merge([
                'host'     => $mysqlConn['hostname'] ?? '127.0.0.1',
                'port'     => (int) ($mysqlConn['hostport'] ?? 3306),
                'database' => $mysqlConn['database'] ?? 'fssoa',
                'username' => $mysqlConn['username'] ?? 'root',
                'password' => $mysqlConn['password'] ?? '',
                'charset'  => $mysqlConn['charset']  ?? 'utf8mb4',
            ], $databaseConfig['pool']);
            PoolManager::register('mysql.default', new MysqlPool($mysqlPoolConfig));
            log_info(sprintf(
                '[HTTP-Worker #%d] MySQL 连接池已初始化，空闲：%d / 最大：%d',
                $worker->id,
                $mysqlPoolConfig['min_connections'] ?? 2,
                $mysqlPoolConfig['max_connections'] ?? 10
            ));
        }
    } catch (\Throwable $e) {
        log_info('[HTTP-Worker] 连接池初始化失败（降级为直连）：' . $e->getMessage());
    }

    // 定时任务：内存监控、日志轮转、健康检查（生产不监控文件变更）
    Timer::add(MEMORY_CHECK_INTERVAL, function() use ($worker) {
        update_health($worker);
        rotate_logs();

        $pid  = getmypid();
        $time = date('Y-m-d H:i:s');

        $memoryReal = memory_get_usage(true) / 1048576;
        Worker::log("[{$time}] [Memory] HTTP-Worker #{$worker->id} PID {$pid} real:{$memoryReal}MB");

        // 连接池统计日志
        $poolStats = PoolManager::stats();
        if (!empty($poolStats)) {
            $statStr = implode(' ', array_map(
                fn($n, $s) => "{$n}[idle:{$s['idle']} active:{$s['active']} max:{$s['max']}]",
                array_keys($poolStats),
                $poolStats
            ));
            Worker::log("[{$time}] [Pool] HTTP-Worker #{$worker->id} {$statStr}");
        }

        // 内存超限则平滑重启当前 worker
        if ($memoryReal > MEMORY_LIMIT_MB) {
            Worker::log("[{$time}] [Warning] HTTP-Worker #{$worker->id} PID {$pid} memory exceeded limit ({$memoryReal} MB > " . MEMORY_LIMIT_MB . " MB), stopping...");
            $worker->stop();
        }
    });
};

// ----------------------------------------------------------------------
// HTTP Worker 停止回调（关闭连接池）
// ----------------------------------------------------------------------
$httpWorker->onWorkerStop = function(Worker $worker) {
    log_info(sprintf('[HTTP-Worker #%d] 正在关闭连接池...', $worker->id));
    PoolManager::closeAll();
    log_info(sprintf('[HTTP-Worker #%d] 连接池已关闭', $worker->id));
};

// ----------------------------------------------------------------------
// HTTP 请求处理回调
// ----------------------------------------------------------------------
$httpWorker->onMessage = function(TcpConnection $connection, WorkermanRequest $req) use (&$framework) {
    $symReq = null;
    $symRes = null;

    try {
        // ==================== 静态文件处理 ====================
        $uri      = $req->uri();
        $pathInfo = parse_url($uri, PHP_URL_PATH);

        $staticDirs   = ['/uploads', '/assets', '/css', '/js', '/images', '/favicon.ico'];
        $isStaticFile = false;

        foreach ($staticDirs as $dir) {
            if (strpos($pathInfo, $dir) === 0) {
                $isStaticFile = true;
                break;
            }
        }

        if ($isStaticFile) {
            $filePath = __DIR__ . '/public' . $pathInfo;
            $realPath = realpath($filePath);
            $publicDir = realpath(__DIR__ . '/public');

            if ($realPath && strpos($realPath, $publicDir) === 0 && is_file($realPath)) {
                $contentType = get_mime_type($realPath);
                $fileContent = file_get_contents($realPath);

                $headers = [
                    'Content-Type' => $contentType,
                    'Cache-Control' => 'public, max-age=86400',
                ];

                if (preg_match('/\.(jpg|jpeg|png|gif|webp|svg|ico)$/i', $realPath)) {
                    $headers['Cache-Control'] = 'public, max-age=2592000';
                }

                $connection->send(new WorkermanResponse(200, $headers, $fileContent));
                return;
            }

            $connection->send(new WorkermanResponse(404, ['Content-Type' => 'text/plain'], 'File Not Found'));
            return;
        }
        // ==================== 静态文件处理结束 ====================

        // 健康检查端点
        if ($req->path() === '/_health') {
            update_health();
            $data     = file_get_contents(HEALTH_FILE);
            $response = new SymfonyResponse($data, 200, ['Content-Type' => 'application/json']);
            $connection->send(convert_to_workerman_response($response));
            return;
        }

        // 转换请求并处理
        $symReq = convert_to_symfony_request($req);
        $symRes = $framework->handleRequest($symReq);

        // 保存 Session 并清理内存，防止跨请求累积
        if ($symReq->hasSession()) {
            $session = $symReq->getSession();
            $session->save();
            $session->clear();
        }

        // 发送队列中的 Cookie
        app('cookie')->sendQueuedCookies($symRes);

        $connection->send(convert_to_workerman_response($symRes));

    } catch (Throwable $e) {
        $error = "[Error] {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}";
        log_info($error);
        Worker::log($error);
        $connection->send(new WorkermanResponse(500, [], "Internal Error: {$e->getMessage()}"));
    } finally {
        if (isset($symReq) && $symReq->hasSession()) {
            $symReq->getSession()->clear();
        }
        unset($symReq, $symRes);
        gc_collect_cycles();
    }
};

// ----------------------------------------------------------------------
// 运行（仅 HTTP，不含 WebSocket / 队列）
// ----------------------------------------------------------------------
Worker::runAll();
