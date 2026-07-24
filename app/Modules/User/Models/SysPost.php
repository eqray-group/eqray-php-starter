<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Modules\User\Models;

use Framework\Basic\BaseLaORMModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * SysPost 系统岗位模型.
 *
 * 岗位表模型，管理系统中岗位信息
 *
 * @property int       $id         岗位ID
 * @property string    $name       岗位名称
 * @property string    $code       岗位代码
 * @property int       $sort       排序
 * @property int       $status     状态: 1启用, 0禁用
 * @property int       $tenant_id  所属租户ID
 * @property string    $remark     备注
 * @property int       $created_by 创建人ID
 * @property int       $updated_by 更新人ID
 * @property \DateTime $created_at 创建时间
 * @property \DateTime $updated_at 更新时间
 *
 * @property SysUser[] $users 岗位下的用户
 *
 * @property string $create_time
 * @property string $update_time
 * @property string $delete_time
 * @property mixed  $deleted_at
 * @property mixed  $enabled
 */
class SysPost extends BaseLaORMModel
{
    use SoftDeletes;

    /**
     * 自定义时间戳字段名.
     */
    public const CREATED_AT = 'create_time';

    public const UPDATED_AT = 'update_time';

    public const DELETED_AT = 'delete_time';

    // ==================== 状态常量 ====================

    /** @var int 禁用状态 */
    public const ENABLED_DISABLED = 0;

    /** @var int 启用状态 */
    public const ENABLED_ENABLED = 1;

    /**
     * 表名.
     * @var    string
     * @return mixed
     */
    protected $table = 'system_post';

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
        'name',
        'code',
        'sort',
        'status',
        'tenant_id',
        'remark',
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
        'sort'        => 'integer',
        'status'      => 'integer',
        'tenant_id'   => 'integer',
        'created_by'  => 'integer',
        'updated_by'  => 'integer',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'datetime',
    ];

    // ==================== 关联关系 ====================

    /**
     * 岗位下的用户 (多对多).
     *
     * @return BelongsToMany<SysUser, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            SysUser::class,
            'system_user_post',
            'post_id',
            'user_id'
        )->withTimestamps();
    }

    // ==================== 业务方法 ====================

    /**
     * 检查岗位是否被禁用.
     */
    public function isDisabled(): bool
    {
        return $this->enabled === self::ENABLED_DISABLED;
    }

    /**
     * 检查岗位是否启用.
     */
    public function isEnabled(): bool
    {
        return $this->enabled === self::ENABLED_ENABLED;
    }

    /**
     * 检查岗位编码是否唯一
     *
     * @param string $postCode  岗位编码
     * @param int    $excludeId 排除的岗位ID
     */
    public static function isPostCodeUnique(string $postCode, int $excludeId = 0): bool
    {
        $query = self::where('code', $postCode);

        if ($excludeId > 0) {
            $query->where('id', '!=', $excludeId);
        }

        return ! $query->exists();
    }

    /**
     * 检查岗位下是否有用户.
     */
    public function hasUsers(): bool
    {
        return SysUserPost::where('post_id', $this->id)->exists();
    }

    /**
     * 获取岗位下的用户ID列表.
     *
     * @return array<array-key, mixed>
     */
    public function getUserIds(): array
    {
        return SysUserPost::where('post_id', $this->id)->pluck('user_id')->toArray();
    }
}
