<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Config\Support;

class Arr
{
    public static function get(array $array, string $key, mixed $default = null): mixed
    {
        if ($key === '' || $key === null) {
            return $array;
        }
        $segments = explode('.', $key);
        $cursor   = $array;
        foreach ($segments as $seg) {
            if (is_array($cursor) && array_key_exists($seg, $cursor)) {
                $cursor = $cursor[$seg];
            } else {
                return $default;
            }
        }
        return $cursor;
    }
}
