<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Attributes\Routes;

/**
 * PatchMapping - PATCH 请求映射注解.
 *
 * 用于将控制器方法映射为 PATCH 请求路由。
 * 继承 BaseMapping，固定 HTTP 方法为 PATCH。
 *
 * 示例：
 * #[PatchMapping('/users/{id}')]
 * #[PatchMapping('/users/{id}', auth: true, roles: ['admin'])]
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class PatchMapping extends BaseMapping
{
    /**
     * 构造函数.
     *
     * @param string       $path       路由路径
     * @param null|bool    $auth       是否需要认证
     * @param array<mixed> $roles      允许访问的角色列表
     * @param array<mixed> $middleware 中间件列表
     */
    public function __construct(
        string $path,
        ?bool $auth = null,
        array $roles = [],
        array $middleware = []
    ) {
        parent::__construct(
            path: $path,
            methods: ['PATCH'],
            auth: $auth,
            roles: $roles,
            middleware: $middleware
        );
    }
}
