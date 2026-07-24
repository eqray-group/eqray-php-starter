<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace App\Providers;

use Framework\Container\ServiceProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;

/**
 * 统一注册 App 目录下的所有服务（带路径合法性检查）.
 *
 * - 共享层（Models / Services / Middlewares 等）按固定命名空间注册；
 * - 多模块控制器 app/<Module>/Controllers 自动发现并注册，无需手动配置。
 */
final class AppServiceProvider implements ServiceProviderInterface
{
    /**
     * 公共（跨模块）共享层配置 [命名空间前缀 => 相对路径].
     *
     * 注意：每个模块自己的 Models/Services/Events/Listeners 已随模块自动发现注册，
     * 此处仅注册真正全局共享的层（如 Middlewares）。
     */
    private const SHARED_MODULES = [
        'App\Middlewares\\' => '/Middlewares',
    ];

    /**
     * 注册所有 App 目录下的服务（带路径检查）.
     */
    public function register(ContainerConfigurator $configurator): void
    {
        $services  = $configurator->services();
        $appDir    = \dirname(__DIR__); // 获取 App 目录的绝对路径
        $modulesDir = $appDir . '/modules';

        if (! is_dir($appDir)) {
            throw new \InvalidArgumentException("App 根目录不存在: {$appDir}");
        }

        // 1. 注册公共共享层
        foreach (self::SHARED_MODULES as $namespace => $relativePath) {
            $this->registerDir($services, $appDir, $namespace, $relativePath);
        }

        // 2. 自动发现并注册多模块 app/Modules/<Module>/{Controllers,Models,Services,Events,Listeners}
        if (is_dir($modulesDir)) {
            foreach (array_diff(scandir($modulesDir), ['.', '..']) as $entry) {
                $moduleDir = $modulesDir . '/' . $entry;
                if (! is_dir($moduleDir)) {
                    continue;
                }
                $moduleNs = 'App\\Modules\\' . $entry;
                foreach (['Controllers', 'Models', 'Services', 'Events', 'Listeners'] as $layer) {
                    $this->registerDir(
                        $services,
                        $modulesDir,
                        $moduleNs . '\\' . $layer . '\\',
                        '/' . $entry . '/' . $layer
                    );
                }
                // 模块内其他常见层（Validate/Dao/Repository 等，若存在）
                foreach (['Validate', 'Dao', 'Repository', 'Providers'] as $extra) {
                    $this->registerDir(
                        $services,
                        $modulesDir,
                        $moduleNs . '\\' . $extra . '\\',
                        '/' . $entry . '/' . $extra
                    );
                }
            }
        }
    }

    /**
     * 注册单个目录下的所有类（仅当目录存在时）.
     */
    private function registerDir(
        ServicesConfigurator $services,
        string $appDir,
        string $namespace,
        string $relativePath
    ): void {
        $fullDir  = $appDir . $relativePath;
        if (! is_dir($fullDir)) {
            return;
        }

        $services
            ->load($namespace, $fullDir . '/**/*.php')
            ->autowire()      // 自动注入依赖
            ->autoconfigure() // 自动配置（如标签、别名等）
            ->public();       // 标记为公开服务，支持动态获取
    }

    /**
     * 启动回调（如需初始化逻辑可在这里添加）.
     */
    public function boot(ContainerInterface $container): void
    {
        // 可选：添加全局启动逻辑
    }
}
