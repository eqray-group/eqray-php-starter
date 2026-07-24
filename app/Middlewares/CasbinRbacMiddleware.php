<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Middlewares;

use App\Modules\System\Services\Casbin\CasbinService;
use Framework\Basic\BaseJsonResponse;
use Framework\Security\CasbinRbac;
use Symfony\Component\HttpFoundation\Request;

/**
 * Casbin RBAC 路由权限校验中间件
 * 适配自研框架，自动拦截无权限请求
 */
class CasbinRbacMiddleware
{
    /**
     * Casbin 权限核心服务
     * @var    CasbinRbac
     * @return mixed
     */
    protected $casbinRbac;

    /**
     * Casbin 权限服务
     * @var    CasbinService
     * @return mixed
     */
    protected $casbinService;

    /**
     * 无需校验的路由白名单.
     * @var    array<array-key, mixed>
     * @return mixed
     */
    protected $whiteRoutes = [
        '/api/core/login',
        '/api/core/logout',
        '/api/core/captcha',
        '/api/core/refresh',
    ];

    /**
     * @return mixed
     */
    public function __construct()
    {
        // 从自研框架容器获取 Casbin 服务实例
        $this->casbinRbac    = new CasbinRbac(config('casbin'));
        $this->casbinService = app(CasbinService::class);
    }

    /**
     * 中间件处理逻辑.
     * @param  Request  $request 自研框架请求对象
     * @param  \Closure $next    下一个中间件/控制器
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        // 1. 获取当前请求的资源路径和方法
        $currentPath = $this->normalizePath($request->getPathInfo()); // 如 /api/user/list

        $currentMethod = strtoupper($request->getMethod()); // 如 GET/POST

        // 2. 白名单路由直接放行
        if (in_array($currentPath, $this->whiteRoutes)) {
            return $next($request);
        }

        // 3. 获取当前登录用户 ID
        $userId = $this->getCurrentUserId($request);

        // 4. 用户未登录，直接返回未授权
        if (empty($userId)) {
            return $this->responseError(401, '请先登录');
        }

        // 5. 调用 Casbin 进行权限校验
        $isAllowed = $this->casbinService->checkPermission(
            $userId,
            $currentPath,
            $currentMethod
        );

        // 6. 无权限，返回 403
        if (! $isAllowed) {
            return $this->responseError(403, '无权限访问该接口');
        }

        // 7. 有权限，继续执行后续逻辑
        return $next($request);
    }

    /**
     * 标准化路径（去除末尾斜杠、统一小写）.
     */
    protected function normalizePath(string $path): string
    {
        $path = rtrim($path, '/');
        return empty($path) ? '/' : $path;
    }

    /**
     * 获取当前登录用户 ID（需适配自研框架的用户认证）.
     * @return null|int|string
     */
    protected function getCurrentUserId(Request $request)
    {
        // 1) 优先复用上游 AuthMiddleware 注入的用户上下文
        $currentUser = $request->attributes->get('current_user');
        if (is_object($currentUser) && isset($currentUser->id)) {
            return $currentUser->id;
        }

        $user   = $request->attributes->get('user');
        $userId = (int) ($user['id'] ?? 0);
        if ($userId > 0) {
            return $userId;
        }

        $requestUserId = (int) ($request->attributes->get('_user_id') ?? 0);
        if ($requestUserId > 0) {
            return $requestUserId;
        }

        // 2) 兜底：从 token 解析（兼容 header/cookie）
        $accessToken = $this->extractAccessToken($request);
        if ($accessToken === null || $accessToken === '') {
            return null;
        }

        try {
            $parsed = app('jwt')->parseForAccess($accessToken);
            $claims = $parsed->claims();
            $uid    = (int) $claims->get('uid');
            return $uid > 0 ? $uid : null;
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * 解析 access token（优先 Authorization，其次 cookie）.
     */
    protected function extractAccessToken(Request $request): ?string
    {
        $header = $request->headers->get('Authorization');
        if (is_string($header) && preg_match('/^\s*Bearer\s+(.+)$/i', $header, $matches)) {
            return trim($matches[1]);
        }

        $cookieToken = $request->cookies->get('access_token');
        return is_string($cookieToken) && $cookieToken !== '' ? $cookieToken : null;
    }

    /**
     * 统一错误响应格式.
     * @param  int    $code 状态码
     * @param  string $msg  错误信息
     * @return mixed
     */
    protected function responseError(int $code, string $msg)
    {
        // 使用框架的 BaseJsonResponse 返回错误
        if ($code === 401) {
            return BaseJsonResponse::unauthorized($msg);
        }
        return BaseJsonResponse::error($msg, $code);
    }
}
