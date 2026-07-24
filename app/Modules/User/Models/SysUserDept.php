<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Modules\User\Models;

use Framework\Basic\BaseLaORMModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * SysUserDept 用户-部门关联模型.
 *
 * @property int       $id          主键ID
 * @property int       $user_id     用户ID
 * @property int       $dept_id     部门ID
 * @property null|int  $created_by  创建人ID
 * @property null|int  $updated_by  更新人ID
 * @property \DateTime $create_time 创建时间
 * @property \DateTime $update_time 更新时间
 *
 * @property SysUser $user 关联的用户
 * @property SysDept $dept 关联的部门
 *
 * @property mixed $status
 * @property mixed $remark
 * @property mixed $delete_time
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $deleted_at
 */
class SysUserDept extends BaseLaORMModel
{
    /**
     * 自定义时间戳字段名.
     */
    public const CREATED_AT = 'create_time';

    public const UPDATED_AT = 'update_time';

    /**
     * 表名.
     * @var    string
     * @return mixed
     */
    protected $table = 'system_user_dept';

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
        'dept_id',
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
        'dept_id'     => 'integer',
        'created_by'  => 'integer',
        'updated_by'  => 'integer',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];

    // ==================== 关联关系 ====================

    /**
     * 关联的用户.
     *
     * @return BelongsTo<SysUser, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(SysUser::class, 'user_id', 'id');
    }

    /**
     * 关联的部门.
     *
     * @return BelongsTo<SysDept, $this>
     */
    public function dept(): BelongsTo
    {
        return $this->belongsTo(SysDept::class, 'dept_id', 'id');
    }

    // ==================== 业务方法 ====================

    /**
     * 获取用户的部门ID.
     *
     * @param  int      $userId 用户ID
     * @return null|int 部门ID，不存在返回null
     */
    public static function getDeptIdByUser(int $userId): ?int
    {
        $record = self::where('user_id', $userId)->first();

        return $record ? $record->dept_id : null;
    }

    /**
     * 同步用户部门关联.
     *
     * 如果记录存在则更新，不存在则创建
     *
     * @param int $userId     用户ID
     * @param int $deptId     部门ID
     * @param int $operatorId 操作人ID
     */
    public static function syncUserDept(int $userId, int $deptId, int $operatorId): void
    {
        $record = self::where('user_id', $userId)->first();

        if ($record) {
            // 更新现有记录
            $record->dept_id    = $deptId;
            $record->updated_by = $operatorId;
            $record->save();
        } else {
            // 创建新记录
            self::create([
                'user_id'    => $userId,
                'dept_id'    => $deptId,
                'created_by' => $operatorId,
                'updated_by' => $operatorId,
            ]);
        }
    }

    /**
     * 获取部门下的所有用户ID.
     *
     * @param  int                     $deptId 部门ID
     * @return array<array-key, mixed> 用户ID数组
     */
    public static function getUsersByDept(int $deptId): array
    {
        return self::where('dept_id', $deptId)
            ->pluck('user_id')
            ->toArray();
    }

    /**
     * 获取用户的部门关联.
     *
     * @param  int                     $userId 用户ID
     * @return array<array-key, mixed> 格式：[['dept_id' => 5], ...]
     */
    public static function getDeptsByUser(int $userId): array
    {
        return self::where('user_id', $userId)
            ->get(['dept_id'])
            ->toArray();
    }
}
