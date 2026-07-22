<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Config\Loader;

use Framework\Config\Exception\ConfigException;

class IniFileLoader implements LoaderInterface
{
    public function load(string $filePath): array
    {
        if (! is_file($filePath)) {
            throw new ConfigException("INI config file not found: {$filePath}");
        }
        $data = parse_ini_file($filePath, true, INI_SCANNER_TYPED);
        if ($data === false) {
            throw new ConfigException("Invalid INI config file: {$filePath}");
        }
        return $data;
    }
}
