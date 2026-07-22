<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Attributes;

/**
 * MiddlewareProviderInterface - 中间件提供者接口.
 *
 * 任何实现了此接口的注解，都会被 Loader 自动提取并注册其对应的中间件。
 * 这提供了一种灵活的方式，让注解能够声明其需要的中间件。
 *
 * 实现此接口的注解类：
 * - Auth：认证中间件
 * - Cache：缓存中间件
 * - Log：日志中间件
 * - Role：角色权限中间件
 * - UserAction：用户行为记录中间件
 * - Validate：参数验证中间件
 * - Middlewares：通用中间件容器
 */
interface MiddlewareProviderInterface
{
    /**
     * 返回该注解关联的中间件类名.
     *
     * 可以返回单个类名字符串，或类名数组。
     *
     * @return array<string>|string 中间件类名或类名数组
     */
    public function getMiddleware(): array|string;
}
