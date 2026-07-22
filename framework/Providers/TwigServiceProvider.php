<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Providers;

use Framework\Container\ServiceProviderInterface;
use Framework\Security\CsrfTokenManager;
use Framework\View\AppTwigExtension;
use Framework\View\MarkdownExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

/*
* 注册twig全局服务
*/
final class TwigServiceProvider implements ServiceProviderInterface
{
    // public function __invoke(ContainerConfigurator $configurator): void
    public function register(ContainerConfigurator $configurator): void
    {
        $services = $configurator->services();

        // TWIG配置加载
        $TempConfig = require BASE_PATH . '/config/view.php';
        $viewConfig = $TempConfig['Twig'];
        $services->set(FilesystemLoader::class)->args([$viewConfig['paths']])->public();

        // 注册 AppTwigExtension 扩展
        $services->set(AppTwigExtension::class)
            ->args([
                service(CsrfTokenManager::class),
                '_token', // 👈 显式传入字段名
            ])
            ->public();

        // 注册 markdown 服务开始
        $services->set(CommonMarkCoreExtension::class)
            ->public();

        // 注册 markdown Environment
        $services->set(\League\CommonMark\Environment\Environment::class)
            ->args([
                [
                    // 这是传递给 Environment 构造函数的配置数组
                    'html_input'         => 'strip',
                    'allow_unsafe_links' => false,
                ],
            ])->call('addExtension', [service(CommonMarkCoreExtension::class)])
            ->public();    // Environment 对象需要加载核心扩展才能工作

        // 注册 MarkdownConverter 服务
        // 它依赖于上面 Environment 服务。
        $services->set(MarkdownConverter::class)
            ->args([
                service(\League\CommonMark\Environment\Environment::class),
            ])
            ->public();

        // 注册自定义 Markdown Twig 扩展
        // 它依赖于上面 MarkdownConverter 服务
        $services->set(MarkdownExtension::class)
            ->args([
                service(MarkdownConverter::class), // 注入 MarkdownConverter
            ])
            ->public();
        // Markdown Twig 扩展结束

        $services->set(Environment::class) // ✅ 显式指定类
            ->args([
                service(FilesystemLoader::class),
                [
                    'cache'            => $viewConfig['cache_path'], // ✅ 字符串 或 false
                    'debug'            => $viewConfig['debug'],
                    'auto_reload'      => $viewConfig['debug'],
                    'strict_variables' => $viewConfig['strict_variables'],
                ],
            ])
            ->call('addExtension', [service(AppTwigExtension::class)])
            ->call('addExtension', [service(MarkdownExtension::class)]) // ✅ 添加新的 Markdown 扩展
            ->public();

        // 别名
        $services->alias('view', Environment::class)->public();
    }

    public function boot(ContainerInterface $container): void
    # public function boot(ContainerConfigurator $container): void
    {}
}
