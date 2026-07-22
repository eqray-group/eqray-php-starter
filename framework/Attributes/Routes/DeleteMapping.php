<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Attributes\Routes;

/**
 * DeleteMapping - DELETE 请求映射注解.
 *
 * 用于将控制器方法映射为 DELETE 请求路由。
 * 继承 BaseMapping，固定 HTTP 方法为 DELETE。
 *
 * 示例：
 * #[DeleteMapping('/users/{id}')]
 * #[DeleteMapping('/users/{id}', auth: true, roles: ['admin'])]
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class DeleteMapping extends BaseMapping
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
            methods: ['DELETE'],
            auth: $auth,
            roles: $roles,
            middleware: $middleware
        );
    }
}
