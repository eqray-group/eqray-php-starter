<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Attributes;

/*
 * @Log
 * 用于声明需要记录操作日志的控制器/方法。
 * 自动记录访问路径、时间、IP、设备信息及请求参数。
 *
 * 示例：
 * #[Log]
 * #[Log(description: '用户登录操作')]
 * #[Log(description: '获取订单列表', level: 'debug')]
 */

use App\Middlewares\LogMiddleware;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Log implements MiddlewareProviderInterface
{
    /**
     * @param string $description 日志描述/操作名称 (例如: "修改密码")
     * @param string $level       日志级别 (info, debug, error, warn)
     */
    public function __construct(
        public string $description = 'System Access',
        public string $level = 'info'
    ) {}

    // 🔥 告诉 Loader：只要用了我这个注解，就请加载 LogMiddleware
    /**
     * @return array<mixed>|string
     */
    public function getMiddleware(): array|string
    {
        return LogMiddleware::class;
    }
}
