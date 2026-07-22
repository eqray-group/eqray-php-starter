<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Config\Loader;

interface LoaderInterface
{
    /**
     * @return array 返回配置数组（若文件内容无效应返回空数组）
     */
    public function load(string $filePath): array;
}
