<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Dao;

use App\Models\ToolCrontab;
use Framework\Basic\BaseDao;

class ToolCrontabDao extends BaseDao
{
    protected function setModel(): string
    {
        return ToolCrontab::class;
    }
}
