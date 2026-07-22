<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Attributes\Routes;

/**
 * Prefix - 路由前缀注解.
 *
 * 用于在控制器类上定义路由前缀，所有方法路由将自动添加此前缀。
 * 支持统一的中间件绑定和权限控制。
 *
 * 示例：
 * #[Prefix('/api/users')]
 * #[Prefix('/api/admin', middleware: [AuthMiddleware::class])]
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class Prefix
{
    /**
     * 构造函数.
     *
     * @param string       $prefix     路由前缀
     * @param array<mixed> $middleware 中间件列表
     * @param null|bool    $auth       是否需要认证
     * @param array<mixed> $roles      允许访问的角色列表
     */
    public function __construct(
        public string $prefix,
        public array $middleware = [],
        public ?bool $auth = null,
        public array $roles = []
    ) {}
}
