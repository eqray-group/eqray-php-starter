<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\DI\Attribute;

class Scope
{
    public const SINGLETON = 'singleton'; // 单例

    public const PROTOTYPE = 'prototype'; // 多例
}
