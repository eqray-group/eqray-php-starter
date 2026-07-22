<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Controllers;

use App\Models\SysLoginLog;
use App\Models\SysUser;
use App\Models\SysUserRole;
use App\Services\Casbin\CasbinService;
use App\Services\IpLocationService;
use App\Services\LoginLogService;
use App\Services\SysUserService;
use Framework\Attributes\Route;
use Framework\Basic\BaseController;
use Framework\Basic\BaseJsonResponse;
use Framework\Utils\JwtFactory;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends BaseController
{
    protected SysUserService $userService;

    protected array $jwtConfig;

    protected CasbinService $casbinService;

    protected LoginLogService $loginLogService;

    protected IpLocationService $ipLocationService;

    protected JwtFactory $jwt;

    #[Route(path: '/api/core/login', methods: ['POST'], name: 'auth.login')]
    public function login(Request $request): BaseJsonResponse|JsonResponse
    {
        $username = '';
        try {
            $jsonBody = [];
            $content  = $request->getContent();
            if (! empty($content)) {
                $decoded = json_decode($content, true);
                if (is_array($decoded)) {
                    $jsonBody = $decoded;
                }
            }
            $all = array_merge($request->query->all(), $request->request->all(), $jsonBody);

            $username = $all['username']         ?? '';
            $password = $all['password']         ?? '';
            $code     = $all['code']             ?? '';
            $uuid     = $all['uuid']             ?? '';
            $remember = $all['rememberPassword'] ?? false;

            if (empty($username) || empty($password)) {
                $this->recordLoginLog($request, (string) $username, false, '用户名和密码不能为空');
                return $this->fail('用户名和密码不能为空');
            }

            if (empty($code) || empty($uuid)) {
                $this->recordLoginLog($request, (string) $username, false, '验证码不能为空');
                return $this->fail('验证码不能为空');
            }

            $user = SysUser::where('username', $username)->first();
            if (! $user || ! $user->verifyPassword($password)) {
                $this->recordLoginLog($request, $username, false, '用户名或密码错误');
                return $this->fail('用户名或密码错误', 400);
            }

            if ($user->isDisabled()) {
                $this->recordLoginLog($request, $username, false, '账号已被禁用');
                return $this->fail('账号已被禁用', 403);
            }

            $ttl         = $remember ? 604800 : ($this->jwtConfig['ttl'] ?? 3600);
            $tokenResult = $this->jwt->issue([
                'uid'      => $user->id,
                'name'     => $user->username,
                'nickname' => $user->realname,
                'role'     => $user->isSuperAdmin() ? ['super_admin', 'admin'] : 'user',
                'roles'    => $user->isSuperAdmin() ? ['super_admin', 'admin'] : ['user'],
            ], $ttl);

            $refreshToken = $this->jwt->issueRefreshToken($user->id, 604800);

            $this->casbinService->syncUserRolesFromDatabase($user->id);

            $user->updateLoginInfo($this->resolveClientIp($request));

            $this->recordLoginLog($request, $user->username, true, '登录成功');

            $menus       = $user->getMenuTree();
            $permissions = $user->isSuperAdmin() ? ['*'] : $user->getPermissions();

            $response = $this->success([
                'user' => [
                    'id'       => $user->id,
                    'username' => $user->username,
                    'nickname' => $user->realname,
                    'avatar'   => $user->avatar,
                    'is_admin' => $user->isSuperAdmin(),
                ],
                'access_token'  => $tokenResult['token'],
                'refresh_token' => $refreshToken,
                'expires_in'    => $tokenResult['ttl'],
                'menus'         => $menus,
                'permissions'   => $permissions,
            ], '登录成功');

            $this->setAuthCookies($response, $tokenResult['token'], $refreshToken, $request->isSecure());

            return $response;
        } catch (\Exception $e) {
            $this->recordLoginLog($request, (string) $username, false, '登录异常：' . $e->getMessage());
            return $this->fail('error:' . $e->getMessage());
        }
    }

    #[Route(path: '/api/core/refresh', methods: ['POST'], name: 'auth.refresh')]
    public function refresh(Request $request): BaseJsonResponse
    {
        $refreshToken = $request->cookies->get('refresh_token');

        if (! $refreshToken) {
            return $this->fail('Refresh token 不存在，请重新登录', 401);
        }

        try {
            $newRefreshToken = $this->jwt->rotateRefreshToken($refreshToken);
            $userId          = $this->jwt->validateRefreshToken($newRefreshToken);

            $user = SysUser::find($userId);
            if (! $user) {
                return $this->fail('用户不存在', 404);
            }

            $tokenResult = $this->jwt->issue([
                'uid'      => $user->id,
                'name'     => $user->username,
                'nickname' => $user->realname,
                'role'     => $user->isSuperAdmin() ? ['super_admin', 'admin'] : 'user',
                'roles'    => $user->isSuperAdmin() ? ['super_admin', 'admin'] : ['user'],
            ]);

            $response = $this->success([
                'access_token'  => $tokenResult['token'],
                'refresh_token' => $newRefreshToken,
                'expires_in'    => $tokenResult['ttl'],
            ]);

            $this->setAuthCookies($response, $tokenResult['token'], $newRefreshToken, $request->isSecure());

            return $response;
        } catch (\Throwable $e) {
            return $this->fail('Token 刷新失败: ' . $e->getMessage(), 401);
        }
    }

    #[Route(path: '/api/core/logout', methods: ['POST'], name: 'auth.logout')]
    public function logout(Request $request): BaseJsonResponse
    {
        $token        = $request->cookies->get('access_token');
        $refreshToken = $request->cookies->get('refresh_token');

        if ($token) {
            try {
                $this->jwt->revoke($token);
            } catch (\Throwable $e) {
                // ignore
            }
        }

        $response = $this->success([], '登出成功');
        $this->clearAuthCookies($response, $request->isSecure());

        return $response;
    }

    #[Route(path: '/api/core/system/user', methods: ['GET'], name: 'auth.me')]
    public function me(Request $request): BaseJsonResponse
    {
        $userId   = $this->getCurrentUserId($request);

        if (! $userId) {
            return $this->fail('登录信息已过期，请重新登录!', 401);
        }

        $user = SysUser::find($userId);
        if (! $user) {
            return $this->fail('用户不存在', 404);
        }

        $roles = SysUserRole::getRoleCodes($userId);

        $user->load(['posts', 'roles', 'menus']);

        return $this->success([
            'id'         => $user->id,
            'username'   => $user->username,
            'nickname'   => $user->realname,
            'realname'   => $user->realname,
            'email'      => $user->email,
            'phone'      => $user->phone,
            'avatar'     => $user->avatar,
            'gender'     => $user->gender,
            'signed'     => $user->signed,
            'remark'     => $user->remark,
            'login_time' => $user->login_time,
            'login_ip'   => $user->login_ip,
            'is_admin'   => $user->isSuperAdmin(),
            'buttons'    => $user->isSuperAdmin() ? ['*'] : $user->getPermissions(),
            'roles'      => $roles,
            'posts'      => collect($user->posts)->map(fn ($p) => [
                'id'   => $p->id,
                'name' => $p->name,
            ])->values()->all(),
        ]);
    }

    #[Route(path: '/api/core/system/menu', methods: ['GET'], name: 'auth.menus')]
    public function menus(Request $request): BaseJsonResponse
    {
        $userId = $this->getCurrentUserId($request);
        if (! $userId) {
            return $this->fail('未登录', 401);
        }
        $sysUser = SysUser::find($userId);
        if (! $sysUser) {
            return $this->fail('用户不存在', 404);
        }
        return $this->success($sysUser->getMenuTree());
    }

    #[Route(path: '/api/core/system/permissions', methods: ['GET'], name: 'auth.permissions')]
    public function permissions(Request $request): BaseJsonResponse
    {
        $userId = $this->getCurrentUserId($request);
        if (! $userId) {
            return $this->fail('未登录', 401);
        }
        $sysUser = SysUser::find($userId);
        if (! $sysUser) {
            return $this->fail('用户不存在', 404);
        }
        return $this->success($sysUser->getPermissions());
    }

    #[Route(path: '/api/core/user/modifyPassword', methods: ['POST'], name: 'auth.changePassword')]
    public function changePassword(Request $request): BaseJsonResponse
    {
        $userId = $this->getCurrentUserId($request);
        if (! $userId) {
            return $this->fail('未登录', 401);
        }

        $oldPassword = $this->input('oldPassword', '', true, $request);
        $newPassword = $this->input('newPassword', '', true, $request);

        if (empty($oldPassword) || empty($newPassword)) {
            return $this->fail('旧密码和新密码不能为空');
        }
        if (strlen($newPassword) < 6) {
            return $this->fail('新密码长度不能少于6位');
        }

        try {
            $this->userService->changePassword($userId, $oldPassword, $newPassword);
            return $this->success([], '密码修改成功');
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    #[Route(path: '/api/core/user/updateInfo', methods: ['POST'], name: 'auth.updateInfo')]
    public function updateInfo(Request $request): BaseJsonResponse
    {
        $userId = $this->getCurrentUserId($request);
        if (! $userId) {
            return $this->fail('未登录', 401);
        }

        $body = array_merge(
            $request->request->all(),
            (array) json_decode($request->getContent(), true)
        );

        $data = array_filter([
            'realname' => $body['realname'] ?? null,
            'email'    => $body['email']    ?? null,
            'phone'    => $body['phone']    ?? null,
            'gender'   => $body['gender']   ?? null,
            'signed'   => $body['signed']   ?? null,
            'avatar'   => $body['avatar']   ?? null,
        ], fn ($v) => $v !== null);

        try {
            $this->userService->update($userId, $data, $userId);
            $user = SysUser::find($userId);
            return $this->success([
                'id'       => $user->id,
                'username' => $user->username,
                'realname' => $user->realname,
                'nickname' => $user->realname,
                'email'    => $user->email,
                'phone'    => $user->phone,
                'gender'   => $user->gender,
                'signed'   => $user->signed,
                'avatar'   => $user->avatar,
            ], '资料修改成功');
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    protected function initialize(): void
    {
        $this->userService       = new SysUserService();
        $this->jwtConfig         = config('jwt', []);
        $this->casbinService     = new CasbinService();
        $this->loginLogService   = new LoginLogService();
        $this->ipLocationService = new IpLocationService();
        $this->jwt               = app('jwt');
    }

    // ==================== helpers ====================

    protected function getCurrentUserId(Request $request): ?int
    {
        $userId = $request->attributes->get('user')['id'] ?? null;
        if ($userId) {
            return (int) $userId;
        }

        $authHeader = $request->headers->get('Authorization');
        if ($authHeader) {
            $token = $this->extractTokenFromHeader($authHeader);
            if ($token) {
                try {
                    $parsed = $this->jwt->parseForAccess($token);
                    return (int) $parsed->claims()->get('uid');
                } catch (\Throwable $e) {
                    return null;
                }
            }
        }

        $token = $request->cookies->get('access_token');
        if ($token) {
            try {
                $parsed = $this->jwt->parseForAccess($token);
                return (int) $parsed->claims()->get('uid');
            } catch (\Throwable $e) {
                return null;
            }
        }

        return null;
    }

    protected function extractTokenFromHeader(string $authHeader): ?string
    {
        if (preg_match('/^Bearer\s+(.+)$/i', $authHeader, $matches)) {
            return $matches[1];
        }
        return null;
    }

    protected function setAuthCookies(
        BaseJsonResponse $response,
        string $accessToken,
        string $refreshToken,
        bool $isSecure
    ): void {
        $sameSite = 'lax';
        $response->headers->setCookie(new Cookie('access_token', $accessToken, time() + 3600, '/', null, $isSecure, true, false, $sameSite));
        $response->headers->setCookie(new Cookie('refresh_token', $refreshToken, time() + 86400 * 7, '/', null, $isSecure, true, false, $sameSite));
    }

    protected function clearAuthCookies(BaseJsonResponse $response, bool $isSecure): void
    {
        $sameSite = 'lax';
        $response->headers->setCookie(new Cookie('access_token', '', time() - 3600, '/', null, $isSecure, true, false, $sameSite));
        $response->headers->setCookie(new Cookie('refresh_token', '', time() - 3600, '/', null, $isSecure, true, false, $sameSite));
    }

    protected function recordLoginLog(Request $request, string $username, bool $success, string $message = ''): void
    {
        try {
            $userAgent = $request->headers->get('User-Agent', '');
            $ip        = $this->resolveClientIp($request);

            $location = $this->ipLocationService->getLocation($ip);

            $browser = 'Other';
            foreach (['Edg', 'Edge', 'Chrome', 'Firefox', 'Safari', 'MSIE', 'Trident'] as $b) {
                if (stripos($userAgent, $b) !== false) {
                    if ($b === 'Trident' || $b === 'MSIE') {
                        $browser = 'IE';
                    } elseif ($b === 'Edg') {
                        $browser = 'Edge';
                    } else {
                        $browser = $b;
                    }
                    break;
                }
            }
            $os = 'Other';
            foreach (['Windows', 'Mac', 'Linux', 'Android', 'iPhone', 'iPad', 'iOS'] as $o) {
                if (stripos($userAgent, $o) !== false) {
                    $os = in_array($o, ['iPhone', 'iPad', 'iOS'], true) ? 'iOS' : $o;
                    break;
                }
            }

            $this->loginLogService->record([
                'username'    => $username !== '' ? $username : '未知用户',
                'ip'          => $ip,
                'ip_location' => $location,
                'os'          => $os,
                'browser'     => $browser,
                'status'      => $success ? SysLoginLog::STATUS_SUCCESS : SysLoginLog::STATUS_FAIL,
                'message'     => $message,
            ]);
        } catch (\Throwable $e) {
            app('log')->error('AuthController recordLoginLog failed', [
                'username' => $username,
                'success'  => $success,
                'message'  => $message,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    protected function resolveClientIp(Request $request): string
    {
        $candidateIps = [];

        $cfIp = trim((string) $request->headers->get('CF-Connecting-IP', ''));
        if ($cfIp !== '') {
            $candidateIps[] = $cfIp;
        }

        $xForwardedFor = trim((string) $request->headers->get('X-Forwarded-For', ''));
        if ($xForwardedFor !== '') {
            $candidateIps[] = trim(explode(',', $xForwardedFor)[0]);
        }

        $xRealIp = trim((string) $request->headers->get('X-Real-IP', ''));
        if ($xRealIp !== '') {
            $candidateIps[] = $xRealIp;
        }

        $clientIp = trim((string) $request->getClientIp());
        if ($clientIp !== '') {
            $candidateIps[] = $clientIp;
        }

        foreach ($candidateIps as $ip) {
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }

        return '0.0.0.0';
    }
}
