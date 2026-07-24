<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Modules\System\Models;

use Framework\Basic\BaseLaORMModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * ToolCrontabLog 定时任务执行日志模型.
 *
 * @property int         $id             主键
 * @property null|int    $crontab_id     任务ID
 * @property null|string $name           任务名称
 * @property null|string $target         调用目标
 * @property null|string $parameter      调用参数
 * @property null|string $exception_info 异常信息
 * @property int         $status         执行状态 (1成功 2失败)
 * @property null|string $create_time    创建时间
 * @property null|string $update_time    修改时间
 * @property null|string $delete_time    删除时间
 *
 * @property mixed $tenant_id
 * @property mixed $created_by
 * @property mixed $updated_by
 * @property mixed $remark
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $deleted_at
 */
class ToolCrontabLog extends BaseLaORMModel
{
    use SoftDeletes;

    /**
     * 自定义时间戳字段名.
     */
    public const CREATED_AT = 'create_time';

    public const UPDATED_AT = 'update_time';

    public const DELETED_AT = 'delete_time';

    /** 执行状态：成功 */
    public const STATUS_SUCCESS = 1;

    /** 执行状态：失败 */
    public const STATUS_FAIL = 2;

    /**
     * @return mixed
     */
    protected $table = 'tool_crontab_log';

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
        'crontab_id', 'name', 'target', 'parameter',
        'exception_info', 'status', 'create_time', 'update_time', 'delete_time',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'         => 'integer',
        'crontab_id' => 'integer',
        'status'     => 'integer',
    ];

    /**
     * 所属任务
     *
     * @return BelongsTo<ToolCrontab, $this>
     */
    public function crontab(): BelongsTo
    {
        return $this->belongsTo(ToolCrontab::class, 'crontab_id', 'id');
    }
}
