<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Config\Loader;

use Framework\Config\Exception\ConfigException;

class PhpFileLoader implements LoaderInterface
{
    public function load(string $filePath): array
    {
        if (! is_file($filePath)) {
            throw new ConfigException("PHP config file not found: {$filePath}");
        }

        $data = require $filePath;
        if (! is_array($data)) {
            throw new ConfigException("PHP config file must return an array: {$filePath}");
        }

        return $data;
    }
}
