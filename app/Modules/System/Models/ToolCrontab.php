<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Modules\System\Models;

use Framework\Basic\BaseLaORMModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * ToolCrontab 定时任务模型.
 *
 * @property int         $id          主键
 * @property null|string $name        任务名称
 * @property int         $type        任务类型 (1=URL GET 2=URL POST 3=类任务)
 * @property null|string $target      调用目标
 * @property null|string $parameter   调用参数
 * @property null|int    $task_style  执行类型
 * @property null|string $rule        定时表达式
 * @property int         $singleton   是否单次执行 (1是 2否)
 * @property int         $status      状态 (1正常 0停用)
 * @property null|string $remark      备注
 * @property null|int    $created_by  创建者
 * @property null|int    $updated_by  更新者
 * @property null|string $create_time 创建时间
 * @property null|string $update_time 修改时间
 * @property null|string $delete_time 删除时间
 *
 * @property mixed $tenant_id
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $deleted_at
 */
class ToolCrontab extends BaseLaORMModel
{
    use SoftDeletes;

    /**
     * 自定义时间戳字段名.
     */
    public const CREATED_AT = 'create_time';

    public const UPDATED_AT = 'update_time';

    public const DELETED_AT = 'delete_time';

    /** 状态：正常 */
    public const STATUS_NORMAL = 1;

    /** 状态：停用 */
    public const STATUS_DISABLED = 0;

    /** 任务类型：URL GET */
    public const TYPE_URL_GET = 1;

    /** 任务类型：URL POST */
    public const TYPE_URL_POST = 2;

    /** 任务类型：类任务 */
    public const TYPE_CLASS = 3;

    /**
     * @return mixed
     */
    protected $table = 'tool_crontab';

    /**
     * @return mixed
     */
    protected $primaryKey = 'id';

    /**
     * @return mixed
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * @return mixed
     */
    protected $fillable = [
        'name', 'type', 'target', 'parameter', 'task_style',
        'rule', 'singleton', 'status', 'remark',
        'created_by', 'updated_by', 'create_time', 'update_time', 'delete_time',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'         => 'integer',
        'type'       => 'integer',
        'task_style' => 'integer',
        'singleton'  => 'integer',
        'status'     => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /**
     * 执行日志关联.
     *
     * @return HasMany<ToolCrontabLog, $this>
     */
    public function logs(): HasMany
    {
        return $this->hasMany(ToolCrontabLog::class, 'crontab_id', 'id');
    }
}
