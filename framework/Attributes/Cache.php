<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Attributes;

use App\Middlewares\CacheMiddleware;

/**
 * @Cache
 * 用于声明控制器的响应结果需要被缓存。
 * 适用于数据变动不频繁、计算量大的接口（如排行榜、配置信息的获取）。
 *
 * 示例：
 * #[Cache] // 默认缓存 60秒
 * #[Cache(ttl: 300)] // 缓存 5分钟
 * #[Cache(ttl: 600, key: 'home_page_data')] // 自定义 Key
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Cache implements MiddlewareProviderInterface
{
    /**
     * @param int         $ttl 缓存有效期（秒），默认 60 秒
     * @param null|string $key 自定义缓存 Key，留空则根据 URL 自动生成
     */
    public function __construct(
        public int $ttl = 60,
        public ?string $key = null
    ) {}

    /**
     * @return array<mixed>|string
     */
    public function getMiddleware(): array|string
    {
        return CacheMiddleware::class;
    }
}
