<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Providers;

use Framework\Container\ServiceProviderInterface;
use Framework\Log\LoggerService;
use Framework\Utils\LoggerCache;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

final class LoggerServiceProvider implements ServiceProviderInterface
{
    // public function __invoke(ContainerConfigurator $configurator): void
    public function register(ContainerConfigurator $configurator): void
    {
        $services = $configurator->services();
        // 注册 log 服务

        $logConfig = require BASE_PATH . '/config/log.php';

        $services->set(LoggerService::class)
            ->args([$logConfig])
            ->public();

        $services->set('log', LoggerService::class)
            ->args([$logConfig])
            ->public();

        $services->set('log_cache', LoggerCache::class)
            ->args([$logConfig])
            ->public();
    }

    public function boot(ContainerInterface $container): void
    # public function boot(ContainerConfigurator $container): void
    {}
}
