<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Providers;

use Framework\Container\ServiceProviderInterface;
use Framework\Translation\TranslationService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\RequestStack;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

/*
* 注册 Translator 服务
*/
final class TranslationServiceProvider implements ServiceProviderInterface
{
    // public function __invoke(ContainerConfigurator $configurator): void
    public function register(ContainerConfigurator $configurator): void
    {
        $services = $configurator->services();

        // 多国语言翻译
        // 注册 Translator 服务（不设 locale，延迟设置）
        $services->set('translator', TranslationService::class)
            ->args([
                service(RequestStack::class), // 或 RequestStack::class
                '%kernel.project_dir%/resource/translations',
            ])
            ->public();
    }

    public function boot(ContainerInterface $container): void
    # public function boot(ContainerConfigurator $container): void
    {}
}
