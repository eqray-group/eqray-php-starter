<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Providers;

use Framework\Cache\CacheFactory;
use Framework\Container\ServiceProviderInterface;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

final class CacheServiceProvider implements ServiceProviderInterface
{
    public function register(ContainerConfigurator $configurator): void
    {
        $services = $configurator->services();

        $cacheConfig = require BASE_PATH . '/config/cache.php';

        // Symfony Cache 工厂
        $services->set(CacheFactory::class)
            ->args([$cacheConfig])->public();

        // TagAwareAdapter（PSR-6）
        $services->set('sf_cache', TagAwareAdapter::class)
            ->factory([service(CacheFactory::class), 'create'])->public();

        // PSR-16 包装，兼容 app('cache') 调用方
        $services->set('cache', Psr16Cache::class)
            ->args([service('sf_cache')])
            ->public();
    }

    public function boot(ContainerInterface $container): void {}
}
