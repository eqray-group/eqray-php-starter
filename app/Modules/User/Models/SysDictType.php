<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Modules\User\Models;

use Framework\Basic\BaseLaORMModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * SysDictType 数据字典类型模型.
 *
 * @property int       $id         字典类型ID
 * @property string    $dict_name  字典名称
 * @property string    $dict_code  字典标识
 * @property int       $status     状态
 * @property string    $remark     备注
 * @property int       $created_by 创建人ID
 * @property int       $updated_by 更新人ID
 * @property \DateTime $created_at 创建时间
 * @property \DateTime $updated_at 更新时间
 * @property \DateTime $deleted_at 删除时间
 *
 * @property SysDictData[] $dictData 字典数据列表
 *
 * @property mixed  $name
 * @property mixed  $code
 * @property string $create_time
 * @property string $update_time
 * @property string $delete_time
 * @property mixed  $tenant_id
 */
class SysDictType extends BaseLaORMModel
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
    public const STATUS_DISABLED = 0;

    /** @var int 启用状态 */
    public const STATUS_ENABLED = 1;

    /**
     * 表名.
     * @var    string
     * @return mixed
     */
    protected $table = 'system_dict_type';

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
        'status',
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
        'status'      => 'integer',
        'created_by'  => 'integer',
        'updated_by'  => 'integer',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'datetime',
    ];

    // ==================== 关联关系 ====================

    /**
     * 字典数据列表.
     *
     * @return HasMany<SysDictData, $this>
     */
    public function dictData(): HasMany
    {
        return $this->hasMany(SysDictData::class, 'type_id', 'id');
    }

    // ==================== 业务方法 ====================

    /**
     * 检查是否启用.
     */
    public function isEnabled(): bool
    {
        return $this->status === self::STATUS_ENABLED;
    }

    /**
     * 根据字典编码获取字典数据.
     *
     * @param  string                  $dictCode 字典编码
     * @return array<array-key, mixed>
     */
    public static function getDataByCode(string $dictCode): array
    {
        $dictType = self::where('code', $dictCode)
            ->where('status', self::STATUS_ENABLED)
            ->first();

        if (! $dictType) {
            return [];
        }

        return SysDictData::where('type_id', $dictType->id)
            ->where('status', SysDictData::STATUS_ENABLED)
            ->orderBy('sort')
            ->get()
            ->toArray();
    }
}
