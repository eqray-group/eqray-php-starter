<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Dao;

use App\Models\SysConfig;
use Framework\Basic\BaseDao;
use Illuminate\Database\Eloquent\Collection;

/**
 * SysConfigDao 配置项数据访问层
 *
 * 封装配置项相关的数据查询操作
 */
class SysConfigDao extends BaseDao
{
    /**
     * 检查同分组内配置键名是否已存在.
     *
     * @param string $key       配置键名
     * @param int    $groupId   分组ID
     * @param int    $excludeId 排除的ID（用于修改时排除自身）
     */
    public function isKeyExistsInGroup(string $key, int $groupId, int $excludeId = 0): bool
    {
        $query = SysConfig::where('key', $key)->where('group_id', $groupId);
        if ($excludeId > 0) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->exists();
    }

    /**
     * 获取分组下所有配置项.
     *
     * @param  int                        $groupId 分组ID
     * @return Collection<int, SysConfig>
     */
    public function getByGroupId(int $groupId): Collection
    {
        return SysConfig::where('group_id', $groupId)->orderBy('id')->get();
    }

    /**
     * 设置模型类.
     */
    protected function setModel(): string
    {
        return SysConfig::class;
    }
}
