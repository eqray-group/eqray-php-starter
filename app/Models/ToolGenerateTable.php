<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Models;

use Framework\Basic\BaseLaORMModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed $table_name
 */
class ToolGenerateTable extends BaseLaORMModel
{
    use SoftDeletes;

    public const CREATED_AT = 'create_time';

    public const UPDATED_AT = 'update_time';

    public const DELETED_AT = 'delete_time';

    // ========== 生成类型常量 ==========

    /** 压缩包下载 */
    public const GENERATE_TYPE_ZIP  = 1;

    /** 生成到模块 */
    public const GENERATE_TYPE_FILE = 2;

    /** 软删除模型 */
    public const GENERATE_MODEL_SOFT   = 1;

    /** 非软删除模型 */
    public const GENERATE_MODEL_NORMAL = 2;

    /** 单表CRUD */
    public const TPL_CATEGORY_SINGLE = 'single';

    /** 树表CRUD */
    public const TPL_CATEGORY_TREE   = 'tree';

    /**
     * @return mixed
     */
    public $incrementing = true;

    // ========== 基础配置 ==========

    /**
     * @return mixed
     */
    protected $table = 'sa_tool_generate_tables';

    /**
     * @return mixed
     */
    protected $keyType = 'int';

    /**
     * @return mixed
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    // ========== 可填字段 ==========

    /**
     * @return mixed
     */
    protected $fillable = [
        'table_name',
        'table_comment',
        'stub',
        'template',
        'namespace',
        'package_name',
        'business_name',
        'class_name',
        'menu_name',
        'belong_menu_id',
        'tpl_category',
        'generate_type',
        'generate_path',
        'generate_model',
        'generate_menus',
        'build_menu',
        'component_type',
        'options',
        'form_width',
        'is_full',
        'remark',
        'source',
        'created_by',
        'updated_by',
    ];

    // ========== 类型转换 ==========

    /** @var array<string, string> */
    protected $casts = [
        'id'             => 'integer',
        'belong_menu_id' => 'integer',
        'generate_type'  => 'integer',
        'generate_model' => 'integer',
        'build_menu'     => 'integer',
        'component_type' => 'integer',
        'form_width'     => 'integer',
        'is_full'        => 'integer',
        'created_by'     => 'integer',
        'updated_by'     => 'integer',
        'create_time'    => 'datetime',
        'update_time'    => 'datetime',
        'delete_time'    => 'datetime',
    ];

    // ========== 关联关系 ==========

    /**
     * 关联字段配置（一对多）.
     */
    public function columns(): mixed
    {
        return $this->hasMany(ToolGenerateColumn::class, 'table_id', 'id');
    }
}
