<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Dao;

use App\Models\SysRole;
use Framework\Basic\BaseDao;

/**
 * SysRoleDao 角色数据访问层
 *
 * 封装角色相关的数据查询操作
 */
class SysRoleDao extends BaseDao
{
    /**
     * 根据角色编码查找角色.
     *
     * @param string $roleCode 角色编码
     */
    public function findByRoleCode(string $roleCode): ?SysRole
    {
        return $this->getOne(['code' => $roleCode]);
    }

    /**
     * 获取启用的角色列表.
     *
     * @param  int                     $page  页码
     * @param  int                     $limit 每页数量
     * @return array<array-key, mixed>
     */
    public function getEnabledList(int $page = 1, int $limit = 20): array
    {
        return $this->selectList(['status' => SysRole::STATUS_ENABLED], '*', $page, $limit, 'sort asc')->toArray();
    }

    /**
     * 获取所有启用的角色.
     *
     * @return array<array-key, mixed>
     */
    public function getAllEnabled(): array
    {
        return $this->selectList(['status' => SysRole::STATUS_ENABLED], '*', 0, 0, 'sort asc')->toArray();
    }

    /**
     * 获取子角色列表.
     *
     * @param  int                     $parentId 父角色ID
     * @return array<array-key, mixed>
     */
    public function getChildrenByParentId(int $parentId): array
    {
        return $this->selectList(['parent_id' => $parentId], '*', 0, 0, 'sort asc')->toArray();
    }

    /**
     * 检查角色编码是否存在.
     *
     * @param string $roleCode  角色编码
     * @param int    $excludeId 排除的角色ID
     */
    public function isRoleCodeExists(string $roleCode, int $excludeId = 0): bool
    {
        $where = ['code' => $roleCode];
        if ($excludeId > 0) {
            return $this->be($where) && $this->value($where, 'id') != $excludeId;
        }
        return $this->be($where);
    }

    /**
     * 更新角色状态
     *
     * @param int $roleId 角色ID
     * @param int $status 状态
     */
    public function updateStatus(int $roleId, int $status): bool
    {
        return $this->update($roleId, ['status' => $status]);
    }

    /**
     * 获取角色总数.
     *
     * @param array<array-key, mixed> $where 条件
     */
    public function getRoleCount(array $where = []): int
    {
        return $this->count($where);
    }

    /**
     * 获取角色ID列表.
     *
     * @param  array<array-key, mixed> $where 条件
     * @return array<array-key, mixed>
     */
    public function getRoleIds(array $where = []): array
    {
        return $this->getColumn($where, 'id');
    }

    /**
     * 设置模型类.
     */
    protected function setModel(): string
    {
        return SysRole::class;
    }
}
