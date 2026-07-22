<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Dao;

use App\Models\SysPost;
use Framework\Basic\BaseDao;

/**
 * SysPostDao 岗位数据访问层
 *
 * 封装岗位相关的数据查询操作
 */
class SysPostDao extends BaseDao
{
    /**
     * 根据岗位编码查找岗位.
     *
     * @param string $postCode 岗位编码
     */
    public function findByPostCode(string $postCode): ?SysPost
    {
        return $this->getOne(['code' => $postCode]);
    }

    /**
     * 获取启用的岗位列表.
     *
     * @param  int                     $page  页码
     * @param  int                     $limit 每页数量
     * @return array<array-key, mixed>
     */
    public function getEnabledList(int $page = 1, int $limit = 20): array
    {
        return $this->selectList(['status' => SysPost::ENABLED_ENABLED], '*', $page, $limit, 'sort asc')->toArray();
    }

    /**
     * 获取所有启用的岗位.
     *
     * @return array<array-key, mixed>
     */
    public function getAllEnabled(): array
    {
        return $this->selectList(['status' => SysPost::ENABLED_ENABLED], '*', 0, 0, 'sort asc')->toArray();
    }

    /**
     * 检查岗位编码是否存在.
     *
     * @param string $postCode  岗位编码
     * @param int    $excludeId 排除的岗位ID
     */
    public function isPostCodeExists(string $postCode, int $excludeId = 0): bool
    {
        $where = ['code' => $postCode];
        if ($excludeId > 0) {
            return $this->be($where) && $this->value($where, 'id') != $excludeId;
        }
        return $this->be($where);
    }

    /**
     * 更新岗位状态
     *
     * @param int $postId  岗位ID
     * @param int $enabled 状态
     */
    public function updateEnabled(int $postId, int $enabled): bool
    {
        return $this->update($postId, ['enabled' => $enabled]);
    }

    /**
     * 获取岗位总数.
     *
     * @param array<array-key, mixed> $where 条件
     */
    public function getPostCount(array $where = []): int
    {
        return $this->count($where);
    }

    /**
     * 获取岗位ID列表.
     *
     * @param  array<array-key, mixed> $where 条件
     * @return array<array-key, mixed>
     */
    public function getPostIds(array $where = []): array
    {
        return $this->getColumn($where, 'id');
    }

    /**
     * 设置模型类.
     */
    protected function setModel(): string
    {
        return SysPost::class;
    }
}
