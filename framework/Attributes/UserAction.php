<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Attributes;

use App\Middlewares\UserActionMiddleware;

/**
 * @UserAction
 * 用于在控制器业务执行成功后，记录用户行为到数据库表。
 *
 * 示例：
 * #[UserAction(type: 'login')]
 * #[UserAction(type: 'register')]
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class UserAction implements MiddlewareProviderInterface
{
    /**
     * @param null|string $type 动作类型标识
     */
    public function __construct(
        public ?string $type = null
    ) {}

    /**
     * @return array<mixed>|string
     */
    public function getMiddleware(): array|string
    {
        return UserActionMiddleware::class;
    }
}
