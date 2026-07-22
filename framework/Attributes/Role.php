<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Attributes;

use App\Middlewares\RoleMiddleware;

/**
 * @Role
 * 用于限制控制器或方法的访问权限。
 *
 * 示例：
 * #[Role(['admin'])] // 仅管理员可访问
 * #[Role(['admin', 'editor'])] // 管理员和编辑可访问
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Role implements MiddlewareProviderInterface
{
    /**
     * @param array<string> $roles 允许访问的角色代码列表
     */
    public function __construct(
        public array $roles = []
    ) {}

    /**
     * 绑定中间件.
     *
     * @return array<mixed>|string
     */
    public function getMiddleware(): array|string
    {
        return RoleMiddleware::class;
    }
}
