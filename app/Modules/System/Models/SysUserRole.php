<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Modules\System\Models;

use Framework\Basic\BaseLaORMModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * SysUserRole 用户角色关联模型.
 *
 * 多对多关联表模型
 *
 * @property int       $id         主键ID
 * @property int       $user_id    用户ID
 * @property int       $role_id    角色ID
 * @property int       $created_by 创建人ID
 * @property int       $updated_by 更新人ID
 * @property \DateTime $created_at 创建时间
 * @property \DateTime $updated_at 更新时间
 *
 * @property SysUser $user 关联用户
 * @property SysRole $role 关联角色
 *
 * @property string $create_time
 * @property string $update_time
 * @property mixed  $status
 * @property mixed  $remark
 * @property mixed  $delete_time
 * @property mixed  $deleted_at
 */
class SysUserRole extends BaseLaORMModel
{
    /**
     * 自定义时间戳字段名.
     */
    public const CREATED_AT = 'create_time';

    public const UPDATED_AT = 'update_time';

    public const DELETED_AT = 'delete_time';

    /**
     * 是否自动维护时间戳.
     * @var    bool
     * @return mixed
     */
    public $timestamps = true;

    /**
     * 表名.
     * @var    string
     * @return mixed
     */
    protected $table = 'system_user_role';

    /**
     * 主键.
     * @var    string
     * @return mixed
     */
    protected $primaryKey = 'id';

    /**
     * 可填充字段.
     * @var    array<int, string>
     * @return mixed
     */
    protected $fillable = [
        'user_id',
        'role_id',
        'created_by',
        'updated_by',
    ];

    /**
     * 类型转换.
     * @var    array<array-key, mixed>
     * @return mixed
     */
    protected $casts = [
        'id'          => 'integer',
        'user_id'     => 'integer',
        'role_id'     => 'integer',
        'created_by'  => 'integer',
        'updated_by'  => 'integer',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];

    // ==================== 关联关系 ====================

    /**
     * 关联用户.
     *
     * @return BelongsTo<SysUser, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(SysUser::class, 'user_id', 'id');
    }

    /**
     * 关联角色.
     *
     * @return BelongsTo<SysRole, $this>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(SysRole::class, 'role_id', 'id');
    }

    // ==================== 查询方法 ====================

    /**
     * 获取用户的角色ID列表.
     *
     * @param  int                     $userId 用户ID
     * @return array<array-key, mixed> 角色ID列表
     */
    public static function getRoleIdsByUser(int $userId): array
    {
        return self::where('user_id', $userId)
            ->pluck('role_id')
            ->toArray();
    }

    /**
     * 获取用户的所有角色关联.
     *
     * @param  int                     $userId 用户ID
     * @return Collection<int, static>
     */
    public static function getByUserId(int $userId)
    {
        return self::where('user_id', $userId)
            ->with(['role'])
            ->get();
    }

    /**
     * 获取角色的所有用户关联.
     *
     * @param  int                     $roleId 角色ID
     * @return Collection<int, static>
     */
    public static function getByRoleId(int $roleId)
    {
        return self::where('role_id', $roleId)
            ->with(['user'])
            ->get();
    }

    /**
     * 检查用户是否拥有指定角色.
     *
     * @param int $userId 用户ID
     * @param int $roleId 角色ID
     */
    public static function hasRole(int $userId, int $roleId): bool
    {
        return self::where('user_id', $userId)
            ->where('role_id', $roleId)
            ->exists();
    }

    /**
     * 检查用户是否拥有指定角色编码
     *
     * @param int    $userId   用户ID
     * @param string $roleCode 角色编码
     */
    public static function hasRoleCode(int $userId, string $roleCode): bool
    {
        return self::where('user_id', $userId)
            ->whereHas('role', function ($query) use ($roleCode) {
                $query->where('code', $roleCode);
            })
            ->exists();
    }

    /**
     * 获取用户的角色编码列表.
     *
     * @param  int                     $userId 用户ID
     * @return array<array-key, mixed> 角色编码数组
     */
    public static function getRoleCodes(int $userId): array
    {
        return self::where('user_id', $userId)
            ->with('role')
            ->get()
            ->pluck('role.code')
            ->filter()
            ->toArray();
    }

    // ==================== 修改方法 ====================

    /**
     * 批量插入用户角色关联.
     *
     * @param int                     $userId    用户ID
     * @param array<array-key, mixed> $roleIds   角色ID数组
     * @param int                     $createdBy 创建人ID
     */
    public static function batchInsert(
        int $userId,
        array $roleIds,
        int $createdBy = 0
    ): bool {
        if (empty($roleIds)) {
            return false;
        }

        $data = [];
        $now  = date('Y-m-d H:i:s', time());

        foreach ($roleIds as $roleId) {
            $data[] = [
                'user_id'     => $userId,
                'role_id'     => $roleId,
                'created_by'  => $createdBy,
                'updated_by'  => $createdBy,
                'create_time' => $now,
                'update_time' => $now,
            ];
        }

        return (bool) self::insert($data);
    }

    /**
     * 同步用户的角色.
     *
     * 先删除所有角色关联，再插入新的关联
     *
     * @param int                     $userId    用户ID
     * @param array<array-key, mixed> $roleIds   角色ID数组
     * @param int                     $createdBy 创建人ID
     */
    public static function syncUserRoles(
        int $userId,
        array $roleIds,
        int $createdBy = 0
    ): void {
        // 删除用户的角色关联
        self::where('user_id', $userId)->delete();

        // 插入新的角色关联
        if (! empty($roleIds)) {
            self::batchInsert($userId, $roleIds, $createdBy);
        }
    }

    /**
     * 为用户添加单个角色.
     *
     * @param int $userId    用户ID
     * @param int $roleId    角色ID
     * @param int $createdBy 创建人ID
     */
    public static function addRole(
        int $userId,
        int $roleId,
        int $createdBy = 0
    ): ?self {
        // 检查是否已存在
        if (self::hasRole($userId, $roleId)) {
            return null;
        }

        return self::create([
            'user_id'    => $userId,
            'role_id'    => $roleId,
            'created_by' => $createdBy,
            'updated_by' => $createdBy,
        ]);
    }

    /**
     * 移除用户的单个角色.
     *
     * @param int $userId 用户ID
     * @param int $roleId 角色ID
     */
    public static function removeRole(int $userId, int $roleId): bool
    {
        return self::where('user_id', $userId)
            ->where('role_id', $roleId)
            ->delete() > 0;
    }

    /**
     * 删除用户的所有角色关联.
     *
     * @param int $userId 用户ID
     */
    public static function deleteByUserId(int $userId): bool
    {
        return self::where('user_id', $userId)->delete() !== false;
    }

    /**
     * 删除角色的所有用户关联.
     *
     * @param int $roleId 角色ID
     */
    public static function deleteByRoleId(int $roleId): bool
    {
        return self::where('role_id', $roleId)->delete() !== false;
    }
}
