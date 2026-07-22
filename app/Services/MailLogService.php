<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Services;

use App\Dao\SysMailLogDao;
use Framework\Basic\BaseService;

/**
 * @extends BaseService<SysMailLogDao>
 */
class MailLogService extends BaseService
{
    /**
     * @return mixed
     */
    protected SysMailLogDao $mailLogDao;

    /**
     * @return mixed
     */
    public function __construct()
    {
        parent::__construct();
        $this->mailLogDao = new SysMailLogDao();
    }

    /**
     * @param  array<array-key, mixed> $params
     * @return array<array-key, mixed>
     */
    public function getPageList(array $params): array
    {
        return $this->mailLogDao->getPageList($params);
    }

    /**
     * @param array<array-key, mixed> $ids
     */
    public function delete(array $ids): int
    {
        return $this->mailLogDao->deleteByIds($ids);
    }
}
