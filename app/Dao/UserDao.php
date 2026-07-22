<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Dao;

use App\Models\Users;
use Framework\Basic\BaseDao;

/**
 * 用户数据访问层
 * 负责与模型交互，封装数据查询逻辑.
 */
class UserDao extends BaseDao
{
    // 自定义租户字段（可选，默认 tenant_id）
    /**
     * @return mixed
     */
    protected string $tenantField = 'tenant_id';

    /**
     * 【扩展方法】根据租户ID查询用户列表
     * 演示：在基础 CURD 外封装业务查询逻辑.
     * @param  array<array-key, mixed> $where
     * @return array<array-key, mixed>
     */
    public function getListByTenantId(int $tenantId, array $where = []): array
    {
        $where['tenant_id'] = $tenantId;
        return $this->selectList($where, '*', 1, 20, 'id desc')->toArray();
    }

    /**
     * 绑定模型类.
     */
    protected function setModel(): string
    {
        // 直接返回模型类名，交给 BaseDao 初始化 ORM 适配器
        return Users::class;
    }
}
