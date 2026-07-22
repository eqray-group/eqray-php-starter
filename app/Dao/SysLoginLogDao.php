<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Dao;

use App\Models\SysLoginLog;
use Framework\Basic\BaseDao;

/**
 * SysLoginLogDao 登录日志数据访问层
 */
class SysLoginLogDao extends BaseDao
{
    /**
     * 获取用户登录日志列表.
     *
     * @param  int                     $userId 用户ID
     * @param  int                     $page   页码
     * @param  int                     $limit  每页数量
     * @return array<array-key, mixed>
     */
    public function getListByUserId(int $userId, int $page = 1, int $limit = 20): array
    {
        return $this->selectList(['created_by' => $userId], '*', $page, $limit, 'login_time desc')->toArray();
    }

    /**
     * 获取登录统计
     *
     * @param  string                  $startDate 开始日期
     * @param  string                  $endDate   结束日期
     * @return array<array-key, mixed>
     */
    public function getLoginStats(string $startDate, string $endDate): array
    {
        // 这里可以添加更复杂的统计逻辑
        return [];
    }

    /**
     * 统计今日登录次数.
     */
    public function countTodayLogin(): int
    {
        $today = date('Y-m-d');
        return $this->count([
            ['login_time', '>=', $today . ' 00:00:00'],
            ['login_time', '<=', $today . ' 23:59:59'],
            'status' => SysLoginLog::STATUS_SUCCESS,
        ]);
    }

    /**
     * 统计今日登录失败次数.
     */
    public function countTodayFailed(): int
    {
        $today = date('Y-m-d');
        return $this->count([
            ['login_time', '>=', $today . ' 00:00:00'],
            ['login_time', '<=', $today . ' 23:59:59'],
            'status' => SysLoginLog::STATUS_FAIL,
        ]);
    }

    /**
     * 统计指定IP今日登录失败次数.
     *
     * @param string $ip IP地址
     */
    public function countTodayFailedByIp(string $ip): int
    {
        $today = date('Y-m-d');
        return $this->count([
            'ip' => $ip,
            ['login_time', '>=', $today . ' 00:00:00'],
            ['login_time', '<=', $today . ' 23:59:59'],
            'status' => SysLoginLog::STATUS_FAIL,
        ]);
    }

    /**
     * 设置模型类.
     */
    protected function setModel(): string
    {
        return SysLoginLog::class;
    }
}
