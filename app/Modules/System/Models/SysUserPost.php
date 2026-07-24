<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Modules\System\Models;

use Framework\Basic\BaseLaORMModel;

/**
 * SysUserPost 用户岗位关联模型.
 *
 * 用户与岗位的多对多中间表模型
 *
 * @property int $user_id 用户ID
 * @property int $post_id 岗位ID
 *
 * @property int    $status
 * @property int    $created_by
 * @property int    $updated_by
 * @property string $create_time
 * @property string $update_time
 * @property string $delete_time
 * @property mixed  $id
 * @property mixed  $remark
 * @property mixed  $created_at
 * @property mixed  $updated_at
 * @property mixed  $deleted_at
 */
class SysUserPost extends BaseLaORMModel
{
    public const CREATED_AT = 'create_time';

    public const UPDATED_AT = 'update_time';

    public const DELETED_AT = 'delete_time';

    /**
     * 是否自增主键.
     * @var    bool
     * @return mixed
     */
    public $incrementing = true;

    /**
     * 是否包含时间戳.
     * @var    bool
     * @return mixed
     */
    public $timestamps = true;

    /**
     * 表名.
     * @var    string
     * @return mixed
     */
    protected $table = 'system_user_post';

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
        'post_id',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * 类型转换.
     * @var    array<array-key, mixed>
     * @return mixed
     */
    protected $casts = [
        'user_id'     => 'integer',
        'post_id'     => 'integer',
        'status'      => 'integer',
        'created_by'  => 'integer',
        'updated_by'  => 'integer',
        'create_time' => 'datetime',
        'update_time' => 'datetime',
        'delete_time' => 'datetime',
    ];

    // ==================== 业务方法 ====================

    /**
     * 根据用户ID获取岗位ID列表.
     *
     * @param  int                     $userId 用户ID
     * @return array<array-key, mixed>
     */
    public static function getPostIdsByUser(int $userId): array
    {
        return self::where('user_id', $userId)->pluck('post_id')->toArray();
    }

    /**
     * 根据岗位ID获取用户ID列表.
     *
     * @param  int                     $postId 岗位ID
     * @return array<array-key, mixed>
     */
    public static function getUserIdsByPost(int $postId): array
    {
        return self::where('post_id', $postId)->pluck('user_id')->toArray();
    }

    /**
     * 批量保存用户岗位关联.
     *
     * @param int                     $userId   用户ID
     * @param array<array-key, mixed> $postIds  岗位ID列表
     * @param int                     $operator 操作人ID
     */
    public static function saveUserPosts(int $userId, array $postIds, int $operator = 0): void
    {
        // 先删除原有关联
        self::where('user_id', $userId)->delete();

        // 批量插入新关联
        if (! empty($postIds)) {
            $data = array_map(function ($postId) use ($userId, $operator) {
                return [
                    'user_id'    => $userId,
                    'post_id'    => $postId,
                    'created_by' => $operator,
                    'updated_by' => $operator,
                ];
            }, $postIds);

            self::insert($data);
        }
    }

    /**
     * 批量保存岗位用户关联.
     *
     * @param int                     $postId   岗位ID
     * @param array<array-key, mixed> $userIds  用户ID列表
     * @param int                     $operator 操作人ID
     */
    public static function savePostUsers(int $postId, array $userIds, int $operator = 0): void
    {
        // 先删除原有关联
        self::where('post_id', $postId)->delete();

        // 批量插入新关联
        if (! empty($userIds)) {
            $data = array_map(function ($userId) use ($postId, $operator) {
                return [
                    'user_id'    => $userId,
                    'post_id'    => $postId,
                    'created_by' => $operator,
                    'updated_by' => $operator,
                ];
            }, $userIds);

            self::insert($data);
        }
    }

    /**
     * 检查用户是否拥有指定岗位.
     *
     * @param int $userId 用户ID
     * @param int $postId 岗位ID
     */
    public static function hasPost(int $userId, int $postId): bool
    {
        return self::where('user_id', $userId)
            ->where('post_id', $postId)
            ->exists();
    }
}
