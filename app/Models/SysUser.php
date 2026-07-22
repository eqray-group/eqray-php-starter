<?php

declare(strict_types=1);

/**
 * 系统用户模型
 *
 * @package App\Models
 * @author  Genie
 * @date    2026-03-12
 
*/

namespace App\Models;

use Framework\Basic\BaseLaORMModel;
use Psr\SimpleCache\CacheInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * SysUser 系统用户模型
 *
 * 用户表模型，包含用户基本信息、状态管理、角色关联等
 * 支持多租户：用户可属于多个租户，在不同租户拥有不同角色
 *
 * @property int         $id             用户ID
 * @property string      $username       登录账号
 * @property string      $password       密码
 * @property string      $realname       真实姓名
 * @property string      $gender         性别
 * @property string      $avatar         头像
 * @property string      $email          邮箱
 * @property string      $phone          手机号
 * @property string      $signed         个性签名
 * @property string      $dashboard      工作台
 * @property int         $is_super       是否超级管理员
 * @property int         $status         状态 0=禁用 1=启用
 * @property string      $remark         备注
 * @property string|null $login_time     最后登录时间
 * @property string      $login_ip       最后登录IP
 * @property int         $created_by     创建人ID
 * @property int         $updated_by     更新人ID
 * @property \DateTime   $created_at     创建时间
 * @property \DateTime   $updated_at     更新时间
 * @property \DateTime   $deleted_at     删除时间
 *
 * @property-read SysRole[]   $roles      用户角色列表（当前租户）
 * @property-read SysMenu[]   $menus      用户个人菜单（当前租户）
 * @property-read SysDept     $dept       所属部门（当前租户）
 * @property-read SysPost[]   $posts      用户拥有的岗位
 
 * @property int $dept_id
 * @property string $create_time
 * @property string $update_time
 * @property string $delete_time
*/
class SysUser extends BaseLaORMModel
{
    use SoftDeletes;

    /**
     * 表名
     * @var string
     * @return mixed
     */
    protected $table = 'sa_system_user';

    /**
     * 主键
     * @var string
     * @return mixed
     */
    protected $primaryKey = 'id';

    /**
     * 自定义时间戳字段名
     */
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    const DELETED_AT = 'delete_time';

    /**
     * 隐藏字段
     * @var array<string>
     * @return mixed
     */
    protected $hidden = [
        'password',
        'delete_time',
    ];

    /**
     * 可填充字段
     * @var array<int, string>
     * @return mixed
     */
    protected $fillable = [
        'username',
        'password',
        'realname',
        'gender',
        'avatar',
        'email',
        'phone',
        'signed',
        'dashboard',
        'is_super',
        'status',
        'remark',
        'login_time',
        'login_ip',
        'created_by',
        'updated_by',
    ];

    /**
     * 类型转换
     * @var array<array-key, mixed>
     * @return mixed
     */
    protected $casts = [
        'id' => 'integer',
        'status' => 'integer',
        //'dept_id' => 'integer', // 必须保留！
        'is_super' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'login_time' => 'datetime',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'datetime',
    ];

    // ==================== 状态常量 ====================

    /** @var int 禁用状态 */
    public const STATUS_DISABLED = 0;

    /** @var int 启用状态 */
    public const STATUS_ENABLED = 1;

    // ==================== 关联关系 ====================

    /**
     * 用户在各租户的部门关联 (一对多)
     *
     * @return HasMany<SysUserDept, $this>
     */
    public function depts(): HasMany
    {
        return $this->hasMany(SysUserDept::class, 'user_id', 'id');
    }

    /**
     * @return BelongsToMany<SysRole, $this>
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            SysRole::class,
            'sa_system_user_role',
            'user_id',
            'role_id'
        )->withTimestamps();
    }

    /**
     * 用户个人菜单 (多对多)
     *
     * @return BelongsToMany<SysMenu, $this>
     */
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(
            SysMenu::class,
            'sa_system_user_menu',
            'user_id',
            'menu_id'
        )->withTimestamps();
    }

    /**
     * 用户拥有的岗位 (多对多)
     *
     * @return BelongsToMany<SysPost, $this>
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(
            SysPost::class,
            'sa_system_user_post',
            'user_id',
            'post_id'
        );
    }

    // ==================== 业务方法 ====================

    /**
     * 检查用户是否被禁用
     *
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->status === self::STATUS_DISABLED;
    }

    /**
     * 检查用户是否启用
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->status === self::STATUS_ENABLED;
    }

    /**
     * 检查是否为超级管理员
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->is_super === 1;
    }

    /**
     * 验证密码
     *
     * @param string $password 明文密码
     * @return bool
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    /**
     * 设置密码 (加密)
     *
     * @param string $password 明文密码
     * @return void
     */
    public function setPasswordAttribute(string $password): void
    {
        $this->attributes['password'] = password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * 获取用户的所有角色编码（当前租户）
     *
     * @return array<array-key, mixed>
     */
    public function getRoleCodes(): array
    {
        return $this->roles()->where('sa_system_role.status', SysRole::STATUS_ENABLED)->pluck('sa_system_role.code')->toArray();
    }

    /**
     * 获取用户的所有角色ID（当前租户）
     *
     * @return array<array-key, mixed>
     */
    public function getRoleIds(): array
    {
        return $this->roles()->where('sa_system_role.status', SysRole::STATUS_ENABLED)->pluck('sa_system_role.id')->toArray();
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getMergedMenuIds(): array
    {
        $roleIds = $this->getRoleIds();

        if ($this->isSuperAdmin()) {
            return SysMenu::where('status', SysMenu::STATUS_ENABLED)
                ->pluck('id')
                ->toArray();
        }

        $roleMenuIds = [];
        if (!empty($roleIds)) {
            $roleMenuIds = SysRoleMenu::whereIn('role_id', $roleIds)
                ->pluck('menu_id')
                ->toArray();
        }

        $userMenuIds = SysUserMenu::where('user_id', $this->id)
            ->pluck('menu_id')
            ->toArray();

        return array_unique(array_merge($roleMenuIds, $userMenuIds));
    }

    /**
     * 获取用户的菜单列表 (树形结构)
     *
     * 注意：仅返回 type=1(目录) 和 type=2(菜单)，按钮(type=3) 不进入左侧导航；
     * 外链(type=4) 走单独入口（如需展示在左侧再放开）。
     * 普通用户若只授权到子菜单（例如 manage_user），会自动补全其祖先目录(manage)，
     * 保证树结构完整。
     *
     * @return array<array-key, mixed>
     */
    public function getMenuTree(): array
    {
        $cacheKey = 'user_menu_tree_v' . self::getMenuTreeVersion() . '_' . $this->id;

        /** @var \Psr\SimpleCache\CacheInterface $cache */
        $cache = app('cache');

        $cached = $cache->get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        $menuIds = $this->getMergedMenuIds();

        if (empty($menuIds)) {
            return [];
        }

        // 自动补全所有祖先菜单 ID，防止子节点找不到父节点导致整棵树被丢弃
        // 超管已拿到全部菜单，跳过递归查父级以节省查询
        $expandedIds = $this->isSuperAdmin() ? $menuIds : SysMenu::expandWithParentIds($menuIds);

        $menus = SysMenu::whereIn('id', $expandedIds)
            ->where('status', SysMenu::STATUS_ENABLED)
            ->whereIn('type', [SysMenu::TYPE_DIRECTORY, SysMenu::TYPE_MENU, SysMenu::TYPE_LINK])
            ->orderBy('sort')
            ->get()
            ->toArray();

        if (empty($menus)) {
            return [];
        }

        $tree = $this->buildMenuTree($menus, 0);

        // Cache the tree for 300 seconds
        $cache->set($cacheKey, $tree, 600);

        return $tree;
    }

    /**
     * 构建菜单树
     *
     * @param array<array-key, mixed> $menus    菜单列表
     * @param int   $parentId 父ID
     * @return array<array-key, mixed>
     */
    protected function buildMenuTree(array $menus, int $parentId = 0): array
    {
        $tree = [];
        foreach ($menus as $menu) {
            if ((int)$menu['parent_id'] === $parentId) {
                $children = $this->buildMenuTree($menus, (int)$menu['id']);
                if ($children) {
                    $menu['children'] = $children;
                }
                $tree[] = $menu;
            }
        }
        return $tree;
    }

    /**
     * Get user permission slugs
     *
     * @return array<array-key, mixed>
     */
    public function getPermissions(): array
    {
        $menuIds = $this->getMergedMenuIds();

        if (empty($menuIds)) {
            return [];
        }

        return SysMenu::whereIn('id', $menuIds)
            ->where('status', SysMenu::STATUS_ENABLED)
            ->where('slug', '!=', '')
            ->pluck('slug')
            ->toArray();
    }

    /**
     * Update last login info
     *
     * @param string $ip Login IP
     * @return void
     */
    public function updateLoginInfo(string $ip): void
    {
        $this->login_ip = $ip;
        $this->login_time = date('Y-m-d H:i:s',time());
        $this->save();
    }

    /**
     * @return void
     */
    public static function clearMenuTreeCache(int $userId): void
    {
        $cacheKey = 'user_menu_tree_v' . self::getMenuTreeVersion() . '_' . $userId;
        $cache = app('cache');
        $cache->delete($cacheKey);
    }

    /**
     * Clear all users menu tree cache by incrementing version
     *
     * Call when menu structure/status changes to invalidate all cached trees at once.
     * @return void
     */
    public static function clearAllMenuTreeCache(): void
    {
        $cache = app('cache');
        $version = (int)$cache->get('user_menu_tree_version', 0);
        $cache->set('user_menu_tree_version', $version + 1, 86400);
    }

    /**
     * Get the current menu tree cache version
     *
     * @return int
     */
    public static function getMenuTreeVersion(): int
    {
        return (int)app('cache')->get('user_menu_tree_version', 0);
    }
}