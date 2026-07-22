<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Dao;

use App\Models\SysDictData;
use App\Models\SysDictType;
use Framework\Basic\BaseDao;

/**
 * SysDictDataDao 数据字典数据数据访问层
 */
class SysDictDataDao extends BaseDao
{
    /**
     * 根据字典类型ID获取数据列表.
     *
     * @param  int                     $dictTypeId 字典类型ID
     * @return array<array-key, mixed>
     */
    public function getListByDictTypeId(int $dictTypeId): array
    {
        return $this->selectList(
            ['type_id' => $dictTypeId, 'status' => SysDictData::STATUS_ENABLED],
            '*',
            0,
            0,
            'sort asc'
        )->toArray();
    }

    /**
     * 根据字典编码获取数据列表.
     *
     * @param  string                  $dictCode 字典编码
     * @return array<array-key, mixed>
     */
    public function getListByDictCode(string $dictCode): array
    {
        $dictType = SysDictType::where('code', $dictCode)
            ->where('status', SysDictType::STATUS_ENABLED)
            ->first();

        if (! $dictType) {
            return [];
        }

        return $this->getListByDictTypeId($dictType->id);
    }

    /**
     * 检查字典值是否存在.
     *
     * @param int    $dictTypeId 字典类型ID
     * @param string $dictValue  字典值
     * @param int    $excludeId  排除的ID
     */
    public function isDictValueExists(int $dictTypeId, string $dictValue, int $excludeId = 0): bool
    {
        $where = ['type_id' => $dictTypeId, 'value' => $dictValue];
        if ($excludeId > 0) {
            return $this->be($where) && $this->value($where, 'id') != $excludeId;
        }
        return $this->be($where);
    }

    /**
     * 删除字典类型下的所有数据.
     *
     * @param int $dictTypeId 字典类型ID
     */
    public function deleteByDictTypeId(int $dictTypeId): bool
    {
        return $this->delete(['type_id' => $dictTypeId]) !== false;
    }

    /**
     * 获取字典标签.
     *
     * @param int    $dictTypeId 字典类型ID
     * @param string $dictValue  字典值
     */
    public function getDictLabel(int $dictTypeId, string $dictValue): string
    {
        return $this->value([
            'type_id' => $dictTypeId,
            'value'   => $dictValue,
            'status'  => SysDictData::STATUS_ENABLED,
        ], 'label') ?? '';
    }

    /**
     * 设置模型类.
     */
    protected function setModel(): string
    {
        return SysDictData::class;
    }
}
