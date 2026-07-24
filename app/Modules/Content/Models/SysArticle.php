<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Modules\Content\Models;

use App\Traits\DataScopeTrait;
use Framework\Basic\BaseLaORMModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * SysArticle 系统文章模型.
 *
 * 文章表模型，管理系统中文章信息
 *
 * @property int         $id          文章ID
 * @property int         $category_id 分类ID
 * @property string      $title       文章标题
 * @property string      $author      作者
 * @property int         $dept_id     部门ID
 * @property string      $image       封面图片
 * @property string      $describe    文章简介
 * @property string      $content     文章内容
 * @property int         $views       浏览次数
 * @property int         $sort        排序
 * @property int         $status      状态: 1启用, 0禁用
 * @property int         $is_link     是否外链: 1是, 2否
 * @property null|string $link_url    外链地址
 * @property int         $is_hot      是否热门: 1是, 2否
 * @property int         $tenant_id   所属租户ID
 * @property int         $created_by  创建人ID
 * @property int         $updated_by  更新人ID
 * @property \DateTime   $create_time 创建时间
 * @property \DateTime   $update_time 更新时间
 * @property \DateTime   $delete_time 删除时间
 *
 * @property SysArticleCategory $category 所属分类
 *
 * @property mixed $remark
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $deleted_at
 */
class SysArticle extends BaseLaORMModel
{
    use SoftDeletes;
    use DataScopeTrait;

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

    /** @var int 是外链 */
    public const IS_LINK_YES = 1;

    /** @var int 非外链 */
    public const IS_LINK_NO = 2;

    /** @var int 热门 */
    public const IS_HOT_YES = 1;

    /** @var int 非热门 */
    public const IS_HOT_NO = 2;

    /**
     * 表名.
     * @var    string
     * @return mixed
     */
    protected $table = 'article';

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
        'category_id',
        'title',
        'author',
        'dept_id',
        'image',
        'describe',
        'content',
        'views',
        'sort',
        'status',
        'is_link',
        'link_url',
        'is_hot',
        'tenant_id',
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
        'category_id' => 'integer',
        'dept_id'     => 'integer',
        'views'       => 'integer',
        'sort'        => 'integer',
        'status'      => 'integer',
        'is_link'     => 'integer',
        'is_hot'      => 'integer',
        'tenant_id'   => 'integer',
        'created_by'  => 'integer',
        'updated_by'  => 'integer',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'datetime',
    ];

    // ==================== 关联关系 ====================

    /**
     * 关联分类.
     *
     * @return BelongsTo<SysArticleCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(SysArticleCategory::class, 'category_id');
    }

    // ==================== 业务方法 ====================

    /**
     * 增加浏览次数.
     */
    public function incrementViewCount(): int
    {
        return $this->increment('views');
    }

    /**
     * 检查文章是否启用.
     */
    public function isEnabled(): bool
    {
        return $this->status === self::STATUS_ENABLED;
    }

    /**
     * 检查文章是否禁用.
     */
    public function isDisabled(): bool
    {
        return $this->status === self::STATUS_DISABLED;
    }

    /**
     * 检查是否为外链.
     */
    public function isLink(): bool
    {
        return $this->is_link === self::IS_LINK_YES;
    }

    /**
     * 检查是否为热门文章.
     */
    public function isHot(): bool
    {
        return $this->is_hot === self::IS_HOT_YES;
    }
}
