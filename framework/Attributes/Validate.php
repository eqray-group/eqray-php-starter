<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Attributes;

use App\Middlewares\ValidateMiddleware;

/**
 * Validate - 参数验证注解.
 *
 * 用于在控制器方法上声明参数验证规则。
 * 框架会自动调用指定的验证器进行参数校验。
 *
 * 示例：
 * #[Validate(UserValidator::class)] // 使用默认场景
 * #[Validate(UserValidator::class, scene: 'login')] // 指定验证场景
 * #[Validate(UserValidator::class, batch: true)] // 批量验证（返回所有错误）
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
class Validate implements MiddlewareProviderInterface
{
    /**
     * 构造函数.
     *
     * @param string      $validator 验证器类名（需继承 Framework\Validation\Validate）
     * @param null|string $scene     验证场景（可选）
     * @param bool        $batch     是否批量验证（默认 false，一旦出错立即返回）
     */
    public function __construct(
        public string $validator,
        public ?string $scene = null,
        public bool $batch = false
    ) {}

    /**
     * 返回关联的中间件类名.
     *
     * @return array<mixed>|string 中间件类名
     */
    public function getMiddleware(): array|string
    {
        return ValidateMiddleware::class;
    }
}
