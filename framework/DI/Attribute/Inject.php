<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\DI\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Inject
{
    /**
     * @param null|string $id    容器中的标识 ID (接口名或别名)
     * @param string      $scope 作用域
     */
    public function __construct(
        public ?string $id = null,
        public string $scope = Scope::SINGLETON
    ) {}
}
