<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Utils;

use ReflectionNamedType;
use ReflectionType;

/** ponytail: tiny helper so PHPStan sees ReflectionNamedType, not abstract ReflectionType */
final class ReflectionTypes
{
    public static function asNamed(?\ReflectionType $type): ?\ReflectionNamedType
    {
        return $type instanceof \ReflectionNamedType ? $type : null;
    }
}
