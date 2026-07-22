<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\DI\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Context
{
    /**
     * @param string $key 上下文中存储的键名 (如 'request', 'user')
     */
    public function __construct(
        public string $key
    ) {}
}
