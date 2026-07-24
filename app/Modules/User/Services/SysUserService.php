<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Modules\User\Services;

use App\Modules\User\Models\SysMenu;
use App\Modules\User\Models\SysPost;
use App\Modules\User\Models\SysRole;
use App\Modules\User\Models\SysUser;
use App\Modules\User\Models\SysUserDept;
use App\Modules\User\Models\SysUserMenu;
use App\Modules\User\Models\SysUserPost;
use App\Modules\User\Models\SysUserRole;
use App\Modules\Auth\Services\Casbin\CasbinService;
use Framework\Basic\BaseService;

/**
 * SysUserService 用户服务
 *
 * 处理用户相关的业务逻辑
 */
class SysUserService extends BaseService
{
    protected const SYSTEM_PROTECTED_USER_ID = 1;

    protected CasbinService $casbinService;

    public function __construct()
    {
        parent::__construct();
        $this->casbinService = new CasbinService();
    }

    // ==================== 用户认证 ====================

    /**
     * 用户登录.
     *
     * @param  string                       $username 用户名
     * @param  string                       $password 密码
     * @param  string                       $ip       登录 IP
     * @return null|array<array-key, mixed> 成功返回用户信息和 token，失败返回 null
     */
    public function login(string $username, string $password, string $ip = ''): ?array
    {
        // 查找用户
        $user = SysUser::where('username', $username)->first();

        if (! $user) {
            return null;
        }

        if ($user->status === SysUser::STATUS_DISABLED) {
            return null;
        }

        if (! password_verify($password, $user->password)) {
            return null;
        }

        SysUser::where('id', $user->id)->update([
            'login_ip'   => $ip,
            'login_time' => date('Y-m-d H:i:s'),
        ]);

        // 同步用户角色到 Casbin
        $this->casbinService->syncUserRolesFromDatabase($user->id);

        $token = $this->generateJwtToken($user);

        // 获取用户菜单
        $menus       = $user->getMenuTree();
        $permissions = $user->getPermissions();

        return [
            'user'        => $this->formatUser($user),
            'token'       => $token,
            'menus'       => $menus,
            'permissions' => $permissions,
        ];
    }

    // ==================== 用户管理 ====================

    /**
     * 获取用户列表.
     *
     * @param  array<array-key, mixed> $params 查询参数
     * @return array<array-key, mixed>
     */
    public function getList(array $params): array
    {
        $page          = (int) ($params['page'] ?? 1);
        $limit         = (int) ($params['limit'] ?? 20);
        $keyword       = $params['keyword']  ?? '';
        $username      = $params['username'] ?? '';
        $phone         = $params['phone']    ?? '';
        $status        = $params['status']   ?? '';
        $deptId        = $params['dept_id']  ?? '';
        $currentUserId = (int) ($params['current_user_id'] ?? 0);

        $query = SysUser::query();

        // keyword 同时模糊匹配 username / realname / phone
        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('username', 'like', "%{$keyword}%")
                    ->orWhere('realname', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%");
            });
        }

        if ($username !== '') {
            $query->where('username', 'like', "%{$username}%");
        }

        if ($phone !== '') {
            $query->where('phone', 'like', "%{$phone}%");
        }

        if ($status !== '') {
            $query->where('status', (int) $status);
        }

        if ($deptId !== '') {
            $deptUserIds = SysUserDept::where('dept_id', (int) $deptId)
                ->pluck('user_id')
                ->toArray();

            if (empty($deptUserIds)) {
                return [
                    'list'  => [],
                    'total' => 0,
                    'page'  => $page,
                    'limit' => $limit,
                ];
            }

            $query->whereIn('id', $deptUserIds);
        }

        $total = $query->count();
        // 优化：使用 Eloquent 标准的 skip/take 方法
        $list = $query->orderBy('id', 'desc')
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get()
            ->toArray();

        foreach ($list as &$item) {
            $item = $this->formatUser($item);
        }

        return [
            'list'  => $list,
            'total' => $total,
            'page'  => $page,
            'limit' => $limit,
        ];
    }

    /**
     * 获取用户下拉选择列表（专用于选择组件）.
     *
     * @param  array<array-key, mixed> $params 查询参数
     * @return array<array-key, mixed>
     */
    public function getSelectorList(array $params): array
    {
        $page    = max(1, (int) ($params['page'] ?? 1));
        $limit   = max(1, (int) ($params['limit'] ?? 5));
        $keyword = trim((string) ($params['keyword'] ?? ''));
        $status  = $params['status'] ?? '';

        $query = SysUser::query();

        // 搜索用户名 / 姓名 / 手机号
        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('username', 'like', "%{$keyword}%")
                    ->orWhere('realname', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%");
            });
        }

        if ($status !== '') {
            $query->where('status', (int) $status);
        }

        $total = (int) $query->count();

        $list = $query->select(['id', 'username', 'realname', 'phone', 'avatar', 'email', 'status'])
            ->orderBy('id', 'desc')
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get()
            ->toArray();

        return [
            'list'  => $list,
            'total' => $total,
            'page'  => $page,
            'limit' => $limit,
        ];
    }

    /**
     * 获取用户详情.
     *
     * @param  int                          $userId 用户 ID
     * @return null|array<array-key, mixed>
     */
    public function getDetail(int $userId): ?array
    {
        $user = SysUser::find($userId);

        if (! $user) {
            return null;
        }

        $data = $this->formatUser($user);

        $roleIds          = SysUserRole::where('user_id', $userId)->pluck('role_id')->toArray();
        $data['role_ids'] = $roleIds;
        if (! empty($roleIds)) {
            $data['roleList'] = SysRole::whereIn('id', $roleIds)
                ->where('status', SysRole::STATUS_ENABLED)
                ->get()
                ->toArray();
        } else {
            $data['roleList'] = [];
        }

        // 直接通过中间表获取用户岗位（绕过 posts() 关联）
        $postIds          = SysUserPost::where('user_id', $userId)->where('status', SysPost::ENABLED_ENABLED)->pluck('post_id')->toArray();
        $data['post_ids'] = $postIds;
        if (! empty($postIds)) {
            $data['postList'] = SysPost::whereIn('id', $postIds)
                ->where('status', SysPost::ENABLED_ENABLED)
                ->get()
                ->toArray();
        } else {
            $data['postList'] = [];
        }

        $data['menu_ids'] = SysUserMenu::getMenuIdsByUserId($userId);

        return $data;
    }

    /**
     * 创建用户.
     *
     * @param array<array-key, mixed> $data     用户数据
     * @param int                     $operator 操作人 ID
     */
    public function create(array $data, int $operator = 0): ?SysUser
    {
        return $this->transaction(function () use ($data, $operator) {
            if (SysUser::where('username', $data['username'])->exists()) {
                throw new \Exception('用户名已存在');
            }

            if (! empty($data['phone']) && SysUser::where('phone', $data['phone'])->exists()) {
                throw new \Exception('手机号已存在');
            }

            // 设置审计字段
            $data['created_by'] = $operator;
            $data['updated_by'] = $operator;

            // 密码会自动通过模型 mutator 加密
            if (! isset($data['password'])) {
                $data['password'] = '123456'; // 默认密码
            }

            $user = SysUser::create($data);

            if (! empty($data['role_ids'])) {
                SysUserRole::syncUserRoles($user->id, $data['role_ids'], $operator);

                $roles = SysRole::whereIn('id', $data['role_ids'])
                    ->where('status', SysRole::STATUS_ENABLED)
                    ->pluck('code')
                    ->toArray();

                foreach ($roles as $roleCode) {
                    $this->casbinService->addRoleForUser($user->id, $roleCode);
                }
            }

            if (! empty($data['post_ids'])) {
                SysUserPost::saveUserPosts($user->id, $data['post_ids'], $operator);
            }

            if (! empty($data['menu_ids'])) {
                SysUserMenu::syncUserMenus($user->id, $data['menu_ids'], $operator);
            }

            if (! empty($data['dept_id'])) {
                SysUserDept::syncUserDept($user->id, $data['dept_id'], $operator);
            }

            // Clear menu tree cache if user has role/menu assignments
            SysUser::clearMenuTreeCache($user->id);

            return $user;
        });
    }

    /**
     * 更新用户.
     *
     * @param int                     $userId   用户 ID
     * @param array<array-key, mixed> $data     用户数据
     * @param int                     $operator 操作人 ID
     */
    public function update(int $userId, array $data, int $operator = 0): bool
    {
        if ($userId === self::SYSTEM_PROTECTED_USER_ID) {
            throw new \Exception('系统内置用户不允许编辑');
        }
        return $this->transaction(function () use ($userId, $data, $operator) {
            // app('cache')->set('update_user_' . $operator, $data);

            $user = SysUser::find($userId);
            if (! $user) {
                throw new \Exception('用户不存在');
            }

            if (isset($data['username']) && $data['username'] !== $user->username) {
                if (SysUser::where('username', $data['username'])->where('id', '!=', $userId)->exists()) {
                    throw new \Exception('用户名已存在');
                }
            }

            if (isset($data['phone']) && $data['phone'] !== $user->phone) {
                if (SysUser::where('phone', $data['phone'])->where('id', '!=', $userId)->exists()) {
                    throw new \Exception('手机号已存在');
                }
            }

            // 设置审计字段
            $data['updated_by'] = $operator;

            // 如果修改密码，密码会自动通过模型 mutator 加密
            if (isset($data['password']) && ! empty($data['password'])) {
                // 模型自动处理，无需手动加密
            } else {
                unset($data['password']);
            }

            $user->fill($data);
            $user->save();

            if (isset($data['role_ids'])) {
                SysUserRole::syncUserRoles($userId, $data['role_ids'], $operator);
                $this->casbinService->syncUserRolesFromDatabase($userId);
            }

            if (isset($data['post_ids'])) {
                SysUserPost::saveUserPosts($userId, $data['post_ids']);
            }

            if (isset($data['menu_ids'])) {
                SysUserMenu::syncUserMenus($userId, $data['menu_ids'], $operator);
            }

            if (isset($data['dept_id'])) {
                SysUserDept::syncUserDept($userId, $data['dept_id'], $operator);
            }

            // Clear menu tree cache if roles or menus changed
            if (isset($data['role_ids']) || isset($data['menu_ids'])) {
                SysUser::clearMenuTreeCache($userId);
            }

            return true;
        });
    }

    /**
     * 删除用户.
     *
     * @param int $userId 用户 ID
     */
    public function delete(int $userId): bool
    {
        if ($userId === self::SYSTEM_PROTECTED_USER_ID) {
            throw new \Exception('系统内置用户不允许删除');
        }
        $user = SysUser::find($userId);
        if (! $user) {
            return false;
        }

        // 软删除用户
        $user->delete();

        // 删除用户角色关联
        SysUserRole::deleteByUserId($userId);

        // 删除用户岗位关联
        SysUserPost::where('user_id', $userId)->delete();

        // 删除用户菜单关联（所有租户）
        SysUserMenu::deleteByUserId($userId);

        // 删除用户部门关联
        SysUserDept::where('user_id', $userId)->delete();

        // 清除 Casbin 角色
        $this->casbinService->deleteRolesForUser($userId);

        return true;
    }

    /**
     * 更新用户状态
     *
     * @param int $userId 用户 ID
     * @param int $status 状态
     */
    public function updateStatus(int $userId, int $status): bool
    {
        if ($userId === self::SYSTEM_PROTECTED_USER_ID) {
            throw new \Exception('系统内置用户状态不允许修改');
        }
        return SysUser::where('id', $userId)->update(['status' => $status]) > 0;
    }

    /**
     * 重置密码
     *
     * @param int    $userId   用户 ID
     * @param string $password 新密码
     */
    public function resetPassword(int $userId, string $password = '123456'): bool
    {
        $user = SysUser::find($userId);
        if (! $user) {
            return false;
        }

        $user->password = $password;
        return $user->save();
    }

    /**
     * 修改密码
     *
     * @param  int        $userId      用户 ID
     * @param  string     $oldPassword 旧密码
     * @param  string     $newPassword 新密码
     * @throws \Exception
     */
    public function changePassword(int $userId, string $oldPassword, string $newPassword): bool
    {
        $user = SysUser::find($userId);
        if (! $user) {
            throw new \Exception('用户不存在');
        }

        // 验证旧密码
        if (! $user->verifyPassword($oldPassword)) {
            throw new \Exception('旧密码错误');
        }

        $user->password = $newPassword;
        return $user->save();
    }

    /**
     * 清理用户缓存.
     *
     * 清除与用户相关的所有缓存数据
     *
     * @param int $userId 用户 ID
     */
    public function clearCache(int $userId): bool
    {
        // 清理用户权限缓存
        $redis = app('redis');
        if ($redis) {
            $redis->del("user:permissions:{$userId}");
            $redis->del("user:menus:{$userId}");
            $redis->del("user:roles:{$userId}");
        }

        return true;
    }

    /**
     * 获取用户已分配的菜单ID列表.
     *
     * @param  int                     $userId 用户 ID
     * @return array<array-key, mixed>
     */
    public function getUserMenuIds(int $userId): array
    {
        return SysUserMenu::getMenuIdsByUserId($userId);
    }

    /**
     * 保存用户菜单分配（先清理再写入，并清除用户缓存）.
     *
     * @param int                     $userId   用户 ID
     * @param array<array-key, mixed> $menuIds  菜单 ID 数组
     * @param int                     $operator 操作人 ID
     */
    public function saveUserMenus(int $userId, array $menuIds, int $operator = 0): bool
    {
        $user = SysUser::find($userId);
        if (! $user) {
            throw new \Exception('用户不存在');
        }

        $menuIds = SysMenu::expandWithParentIds($menuIds);

        SysUserMenu::syncUserMenus($userId, $menuIds, $operator);

        // 同步用户菜单权限到 Casbin
        $this->casbinService->syncUserMenuPermissions($userId);

        // Clear menu tree cache (framework cache)
        SysUser::clearMenuTreeCache($userId);

        // 清除用户菜单/权限缓存，使变更立即生效
        $redis = app('redis');
        if ($redis) {
            $redis->del("user:permissions:{$userId}");
            $redis->del("user:menus:{$userId}");
            $redis->del("user:roles:{$userId}");
        }

        return true;
    }

    /**
     * 设置用户首页/工作台.
     *
     * @param  int        $userId    用户 ID
     * @param  string     $dashboard 工作台标识
     * @throws \Exception
     */
    public function setHomePage(int $userId, string $dashboard): bool
    {
        $user = SysUser::find($userId);
        if (! $user) {
            throw new \Exception('用户不存在');
        }

        $user->dashboard = $dashboard;
        return $user->save();
    }

    /**
     * 生成 JWT Token.
     *
     * @param SysUser $user 用户
     */
    protected function generateJwtToken(SysUser $user): string
    {
        $jwt         = app('jwt');
        $roles       = $user->getRoleCodes();
        $primaryRole = $roles[0] ?? 'user';

        $tokenData = $jwt->issue([
            'uid'      => $user->id,
            'username' => $user->username,
            'role'     => $primaryRole,
            'roles'    => $roles,
        ]);

        return $tokenData['token'];
    }

    // ==================== 辅助方法 ====================

    /**
     * 格式化用户数据.
     *
     * @param  array<string, mixed>|SysUser $user 用户
     * @return array<array-key, mixed>
     */
    protected function formatUser(array|SysUser $user): array
    {
        if ($user instanceof SysUser) {
            $data = $user->toArray();
        } else {
            $data = $user;
        }

        // 移除敏感字段
        unset($data['password']);

        // 格式化时间
        if (isset($data['create_time'])) {
            $data['create_time'] = is_string($data['create_time'])
                ? $data['create_time']
                : $data['create_time']->format('Y-m-d H:i:s');
        }

        if (isset($data['update_time'])) {
            $data['update_time'] = is_string($data['update_time'])
                ? $data['update_time']
                : $data['update_time']->format('Y-m-d H:i:s');
        }

        // 状态文本
        $data['status_text'] = $data['status'] === SysUser::STATUS_ENABLED ? '启用' : '禁用';

        // 数据库值映射到字典值：DB 1=启用 0=禁用 → 字典 1=正常 2=停用
        if (isset($data['status'])) {
            //   $data['status'] = $data['status'] === 0 ? 2 : 1;
        }

        return $data;
    }
}
