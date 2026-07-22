<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\DI\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Autowire
{
    /**
     * @param string $scope 作用域
     */
    public function __construct(
        public string $scope = Scope::SINGLETON
    ) {}
}
