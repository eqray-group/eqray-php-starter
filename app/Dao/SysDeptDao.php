<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Dao;

use App\Models\SysDept;
use Framework\Basic\BaseDao;

/**
 * SysDeptDao 部门数据访问层
 *
 * 封装部门相关的数据查询操作
 */
class SysDeptDao extends BaseDao
{
    /**
     * 根据部门编码查找部门.
     *
     * @param string $deptCode 部门编码
     */
    public function findByDeptCode(string $deptCode): ?SysDept
    {
        return $this->getOne(['code' => $deptCode]);
    }

    /**
     * 获取启用的部门列表.
     *
     * @param  int                     $page  页码
     * @param  int                     $limit 每页数量
     * @return array<array-key, mixed>
     */
    public function getEnabledList(int $page = 1, int $limit = 20): array
    {
        return $this->selectList(['status' => SysDept::STATUS_ENABLED], '*', $page, $limit, 'sort asc')->toArray();
    }

    /**
     * 获取所有启用的部门.
     *
     * @return array<array-key, mixed>
     */
    public function getAllEnabled(): array
    {
        return $this->selectList(['status' => SysDept::STATUS_ENABLED], '*', 0, 0, 'sort asc')->toArray();
    }

    /**
     * 获取子部门列表.
     *
     * @param  int                     $parentId 父部门ID
     * @return array<array-key, mixed>
     */
    public function getChildrenByParentId(int $parentId): array
    {
        return $this->selectList(['parent_id' => $parentId], '*', 0, 0, 'sort asc')->toArray();
    }

    /**
     * 检查部门编码是否存在.
     *
     * @param string $deptCode  部门编码
     * @param int    $excludeId 排除的部门ID
     */
    public function isDeptCodeExists(string $deptCode, int $excludeId = 0): bool
    {
        $where = ['code' => $deptCode];
        if ($excludeId > 0) {
            return $this->be($where) && $this->value($where, 'id') != $excludeId;
        }
        return $this->be($where);
    }

    /**
     * 更新部门状态
     *
     * @param int $deptId 部门ID
     * @param int $status 状态
     */
    public function updateStatus(int $deptId, int $status): bool
    {
        return $this->update($deptId, ['status' => $status]) > 0;
    }

    /**
     * 获取部门总数.
     *
     * @param array<array-key, mixed> $where 条件
     */
    public function getDeptCount(array $where = []): int
    {
        return $this->count($where);
    }

    /**
     * 获取部门ID列表.
     *
     * @param  array<array-key, mixed> $where 条件
     * @return array<array-key, mixed>
     */
    public function getDeptIds(array $where = []): array
    {
        return $this->getColumn($where, 'id');
    }

    /**
     * 检查部门是否有子部门.
     *
     * @param int $deptId 部门ID
     */
    public function hasChildren(int $deptId): bool
    {
        return $this->be(['parent_id' => $deptId]);
    }

    /**
     * 设置模型类.
     */
    protected function setModel(): string
    {
        return SysDept::class;
    }
}
