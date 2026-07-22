<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Middleware;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CircuitBreakerMiddleware implements MiddlewareInterface
{
    private object $redis;

    /** @var int 失败阈值 */
    private int $failureThreshold;

    /** @var int 熔断超时时间（秒） */
    private int $timeout;

    /** @var string 服务名称（用于 Redis key） */
    private string $serviceName;

    /**
     * @param int    $failureThreshold 连续失败多少次后触发熔断
     * @param int    $timeout          熔断器打开后，保持开启状态的秒数
     * @param string $serviceName      熔断器名称 (例如: 'default', 'payment_api')
     */
    public function __construct(
        object $redisClient,
        int $failureThreshold = 5,
        int $timeout = 10,
        string $serviceName = 'default'
    ) {
        $this->redis            = $redisClient; // 2. 保存它
        $this->failureThreshold = $failureThreshold;
        $this->timeout          = $timeout;
        $this->serviceName      = $serviceName;
    }

    /**
     * 处理请求，实现基于 Redis 的熔断逻辑.
     *
     * @param callable $next 下一个中间件或控制器
     */
    public function handle(Request $request, callable $next): Response
    {
        // 1. 定义原子化的 Redis 键
        // 你可以根据 $request 动态设置 $this->serviceName，实现更细粒度的控制
        $baseKey    = 'breaker:' . $this->serviceName;
        $openKey    = $baseKey . ':open';      // 状态键："open" 状态标记
        $failureKey = $baseKey . ':failures'; // 计数器键：记录连续失败次数

        // 2. 检查熔断器是否处于 "Open" 状态
        // RedisFactory::exists 是原子的。
        if ($this->redis->exists($openKey)) {
            // 状态为 Open，直接熔断，返回 503
            return $this->buildServiceUnavailableResponse($request);
        }

        // 3. 状态为 "Closed" 或 "Half-Open" (openKey 已过期)
        // 允许请求通过
        try {
            $response = $next($request);

            // 检查下游服务是否返回了服务端错误
            if (in_array($response->getStatusCode(), [500, 502, 503, 504], true)) {
                // 主动抛出异常，以便被 catch 块统一处理
                throw new \RuntimeException('Upstream service error', $response->getStatusCode());
            }

            // 4. 请求成功
            // 如果是 "Half-Open" 状态下的成功，删除 failureKey 会使其恢复到 "Closed"
            // 如果是 "Closed" 状态下的成功，删除它（即使不存在）也没问题
            $this->redis->del($failureKey);

            return $response;
        } catch (\Throwable $e) {
            // 5. 请求失败 (来自 $next() 或我们主动抛出的错误)

            // 使用原子自增记录失败次数
            $failures = $this->redis->incr($failureKey);

            // 6. 检查是否达到阈值
            if ($failures >= $this->failureThreshold) {
                // 达到阈值，触发熔断
                // 设置 "Open" 状态键，并给予 $this->timeout 的自动过期时间
                // 使用 ['ex' => $this->timeout] 选项
                $this->redis->set($openKey, 1, $this->timeout);

            // (可选) 我们可以立即删除 failureKey，因为 openKey 已经接管了
            // RedisFactory::del($failureKey);
            } else {
                // 如果是第一次失败，设置一个过期时间，防止这个计数器永久存在
                // * 2 确保它比 openKey 活得久一点
                if ($failures === 1) {
                    $this->redis->expire($failureKey, $this->timeout * 2);
                }
            }

            // 7. 无论如何，本次失败的请求都返回 503
            return $this->buildServiceUnavailableResponse($request);
        }
    }

    /**
     * 构建友好的 503 响应 (与你原来的一致).
     */
    private function buildServiceUnavailableResponse(Request $request): Response
    {
        $message = '服务暂时不可用，请稍后再试。';

        // 判断是否为 API 请求
        if ($request->isXmlHttpRequest()
            || strpos($request->headers->get('Accept', ''), 'application/json') !== false) {
            return new JsonResponse([
                'success' => false,
                'error'   => 'service_unavailable',
                'message' => $message,
                'details' => '系统正在保护性熔断中，稍后自动恢复。',
            ], 503);
        }

        $html = <<<HTML
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>服务不可用</title>
    <style>
        body { font-family: system-ui, sans-serif; text-align: center; padding: 50px; background: #f9f9f9; }
        .box { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #e67e22; font-size: 1.8em; margin-bottom: 20px; }
        p { color: #555; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="box">
        <h1>🔧 服务暂时不可用</h1>
        <p>{$message}</p>
        <p>系统已自动启用熔断机制，预计 {$this->timeout} 秒后自动尝试恢复。</p>
    </div>
</body>
</html>
HTML;

        return new Response($html, 503, ['Content-Type' => 'text/html; charset=utf-8']);
    }
}
