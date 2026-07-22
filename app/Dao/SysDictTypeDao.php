<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Dao;

use App\Models\SysDictType;
use Framework\Basic\BaseDao;

/**
 * SysDictTypeDao 数据字典类型数据访问层
 */
class SysDictTypeDao extends BaseDao
{
    /**
     * 根据字典编码查找.
     *
     * @param string $dictCode 字典编码
     */
    public function findByDictCode(string $dictCode): ?SysDictType
    {
        return $this->getOne(['code' => $dictCode]);
    }

    /**
     * 检查字典编码是否存在.
     *
     * @param string $dictCode  字典编码
     * @param int    $excludeId 排除的ID
     */
    public function isDictCodeExists(string $dictCode, int $excludeId = 0): bool
    {
        $where = ['code' => $dictCode];
        if ($excludeId > 0) {
            return $this->be($where) && $this->value($where, 'id') != $excludeId;
        }
        return $this->be($where);
    }

    /**
     * 获取启用的字典类型列表.
     *
     * @return array<array-key, mixed>
     */
    public function getAllEnabled(): array
    {
        return $this->selectList(['status' => SysDictType::STATUS_ENABLED], '*', 0, 0, 'id desc')->toArray();
    }

    /**
     * 设置模型类.
     */
    protected function setModel(): string
    {
        return SysDictType::class;
    }
}
