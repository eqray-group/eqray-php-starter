<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Modules\System\Models;

use Framework\Basic\BaseLaORMModel;

/**
 * SysUserMenu 用户菜单关联模型.
 *
 * 多对多关联表模型，用于用户个人菜单权限
 *
 * @property int       $id         主键ID
 * @property int       $user_id    用户ID
 * @property int       $menu_id    菜单ID
 * @property int       $created_by 创建人ID
 * @property int       $updated_by 更新人ID
 * @property \DateTime $created_at 创建时间
 * @property \DateTime $updated_at 更新时间
 *
 * @property int    $status
 * @property string $create_time
 * @property string $update_time
 * @property string $delete_time
 * @property mixed  $remark
 * @property mixed  $deleted_at
 */
class SysUserMenu extends BaseLaORMModel
{
    /**
     * 自定义时间戳字段名.
     */
    public const CREATED_AT = 'create_time';

    public const UPDATED_AT = 'update_time';

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
    protected $table = 'system_user_menu';

    /**
     * 主键.
     * @var    string
     * @return mixed
     */
    protected $primaryKey = 'id';
    // const DELETED_AT = 'delete_time';

    /**
     * 可填充字段.
     * @var    array<int, string>
     * @return mixed
     */
    protected $fillable = [
        'user_id',
        'menu_id',
        'created_by',
        'updated_by',
        'status',
    ];

    /**
     * 类型转换.
     * @var    array<array-key, mixed>
     * @return mixed
     */
    protected $casts = [
        'id'          => 'integer',
        'user_id'     => 'integer',
        'menu_id'     => 'integer',
        'created_by'  => 'integer',
        'updated_by'  => 'integer',
        'status'      => 'integer',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        // 'delete_time' => 'datetime',
    ];

    // ==================== 业务方法 ====================

    /**
     * 批量插入用户菜单关联.
     *
     * @param int                     $userId    用户ID
     * @param array<array-key, mixed> $menuIds   菜单ID数组
     * @param int                     $createdBy 创建人ID
     */
    public static function batchInsert(int $userId, array $menuIds, int $createdBy = 0): bool
    {
        if (empty($menuIds)) {
            return false;
        }

        $data = [];
        $now  = date('Y-m-d H:i:s', time());

        foreach ($menuIds as $menuId) {
            $data[] = [
                'user_id'     => $userId,
                'menu_id'     => $menuId,
                'created_by'  => $createdBy,
                'updated_by'  => $createdBy,
                'create_time' => $now,
                'update_time' => $now,
            ];
        }

        return (bool) self::insert($data);
    }

    /**
     * 删除用户的所有菜单关联.
     *
     * @param int $userId 用户ID
     */
    public static function deleteByUserId(int $userId): bool
    {
        return self::where('user_id', $userId)->forceDelete() !== false;
    }

    /**
     * 删除菜单的所有用户关联.
     *
     * @param int $menuId 菜单ID
     */
    public static function deleteByMenuId(int $menuId): bool
    {
        // return self::where('menu_id', $menuId)->forceDelete() !== false;
        self::where('menu_id', $menuId)->delete();
        return true;
    }

    /**
     * 同步用户菜单.
     *
     * @param int                     $userId    用户ID
     * @param array<array-key, mixed> $menuIds   菜单ID数组
     * @param int                     $createdBy 创建人ID
     */
    public static function syncUserMenus(int $userId, array $menuIds, int $createdBy = 0): void
    {
        // 先删除旧关联
        self::deleteByUserId($userId);

        // 再插入新的关联
        if (! empty($menuIds)) {
            self::batchInsert($userId, $menuIds, $createdBy);
        }
    }

    /**
     * 获取用户菜单ID列表.
     *
     * @param  int                     $userId 用户ID
     * @return array<array-key, mixed>
     */
    public static function getMenuIdsByUserId(int $userId): array
    {
        return self::where('user_id', $userId)->pluck('menu_id')->toArray();
    }
}
