<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Dao;

use App\Models\ToolCrontabLog;
use Framework\Basic\BaseDao;

class ToolCrontabLogDao extends BaseDao
{
    protected function setModel(): string
    {
        return ToolCrontabLog::class;
    }
}
