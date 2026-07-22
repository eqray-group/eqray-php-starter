<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Providers;

use Framework\Container\ServiceProviderInterface;
use Framework\Database\DatabaseFactory;
// use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

final class DatabaseServiceProvider implements ServiceProviderInterface
{
    public function register(ContainerConfigurator $configurator): void
    {
        $services = $configurator->services();

        $dbConfig = require BASE_PATH . '/config/database.php';

        $services->set(DatabaseFactory::class)
            ->args([$dbConfig, service('log')])
            ->public();

        $services->set('db', DatabaseFactory::class)
            ->args([$dbConfig, service('log')])
            ->public();
    }

    public function boot(ContainerInterface $container): void {}
}
