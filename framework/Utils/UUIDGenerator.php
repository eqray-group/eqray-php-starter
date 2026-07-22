<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Utils;

class UUIDGenerator
{
    /**
     * 生成UUID.
     *
     * @throws \Exception
     */
    public static function generate(string $format = 'uuid', int $length = 36): ?string
    {
        if ($format === 'uuid') {
            return self::generateUUID();
        }
        if ($format === 'custom') {
            return self::generateCustomUUID($length);
        }
        return null;
    }

    /**
     * 生成标准格式UUID.
     *
     * @throws \Exception
     */
    private static function generateUUID(): string
    {
        // 生成标准UUID
        return sprintf(
            '%s-%s-%s-%s-%s',
            bin2hex(random_bytes(4)),
            bin2hex(random_bytes(2)),
            bin2hex(random_bytes(2)),
            bin2hex(random_bytes(2)),
            bin2hex(random_bytes(6))
        );
    }

    /**
     * 自定义UUID.
     *
     * @param mixed $length
     *
     * @throws \Exception
     */
    private static function generateCustomUUID($length): ?string
    {
        // 生成自定义长度的UUID
        if ($length < 1) {
            return null; // 长度必须大于0
        }
        return strtoupper(substr(bin2hex(random_bytes($length / 2)), 0, $length));
    }
}
