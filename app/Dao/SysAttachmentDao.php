<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Dao;

use App\Models\SysAttachment;
use Framework\Basic\BaseDao;

/**
 * SysAttachmentDao 附件数据访问层
 */
class SysAttachmentDao extends BaseDao
{
    /**
     * 根据 hash 查找附件（秒传）.
     */
    public function findByHash(string $hash): ?SysAttachment
    {
        return $this->getOne(['hash' => $hash]);
    }

    /**
     * 获取分类下附件数量.
     */
    public function getCountByCategoryId(int $categoryId): int
    {
        return $this->count(['category_id' => $categoryId]);
    }

    /**
     * 获取所有附件总字节数.
     */
    public function getTotalSize(): int
    {
        return (int) SysAttachment::sum('size_byte');
    }

    protected function setModel(): string
    {
        return SysAttachment::class;
    }
}
