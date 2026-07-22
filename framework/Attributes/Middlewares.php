<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Attributes;

/**
 * @Middlewares
 * 通用中间件注解，用于在控制器或方法上声明多个中间件。
 * 调度器会自动读取此注解返回的数组，并合并到执行链中。
 *
 * 示例：
 * #[Middlewares([CorsMiddleware::class, RateLimitMiddleware::class])]
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Middlewares implements MiddlewareProviderInterface
{
    /**
     * @param array<string> $middlewares 中间件类名数组
     */
    public function __construct(
        public array $middlewares = []
    ) {}

    /**
     * 直接返回中间件数组.
     *
     * @return array<mixed>|string
     */
    public function getMiddleware(): array|string
    {
        return $this->middlewares;
    }
}
