<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Console\Commands;

use Framework\Core\AttributeRouteLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\Route;

/**
 * 路由列表命令.
 *
 * 显示系统中所有注册的路由，包括：
 * - 手动路由 (config/routes.php)
 * - 主应用注解路由 (app/Controllers)
 *
 * 使用方法：
 *   php novaphp route:list
 *   php novaphp route:list --method=GET
 *   php novaphp route:list --path=/api
 *   php novaphp route:list --name=user
 */
class RouteListCommand extends Command
{
    /**
     * 命令名称.
     *
     * @var string
     */
    protected static $defaultName = 'route:list';

    /**
     * 配置命令.
     */
    protected function configure(): void
    {
        $this->setName('route:list') // ✅ 关键修复
            ->setDescription('列出所有路由')
            ->setHelp('此命令显示系统中所有模块通过 Attribute 注解注册的路由。')
            ->addOption('method', 'm', InputOption::VALUE_OPTIONAL, '按 HTTP 方法筛选')
            ->addOption('path', 'p', InputOption::VALUE_OPTIONAL, '按路径前缀筛选')
            ->addOption('name', null, InputOption::VALUE_OPTIONAL, '按路由名称筛选')
            ->addOption('json', 'j', InputOption::VALUE_NONE, '以 JSON 格式输出');
    }

    /**
     * 执行命令.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // 获取筛选条件
        $methodFilter = strtoupper($input->getOption('method') ?? '');
        $pathFilter   = $input->getOption('path');
        $nameFilter   = $input->getOption('name');
        $jsonOutput   = $input->getOption('json');

        // 收集所有路由
        $allRoutes = [];

        // 1. 自动发现多模块注解路由
        $moduleDirs = $this->discoverModuleControllers();
        foreach ($moduleDirs as $namespace => $dir) {
            $moduleRoutes = $this->loadAnnotatedRoutes($dir, $namespace);
            $source       = $this->moduleNameFromNamespace($namespace);
            foreach ($moduleRoutes as $name => $route) {
                $allRoutes[] = $this->formatRoute($name, $route, $source);
            }
        }

        // 3. 应用筛选
        $allRoutes = $this->filterRoutes($allRoutes, $methodFilter, $pathFilter, $nameFilter);

        // 5. 排序（按路径）
        usort($allRoutes, fn ($a, $b) => strcmp($a['path'], $b['path']));

        // 6. 输出
        if ($jsonOutput) {
            $output->writeln(json_encode($allRoutes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } else {
            $this->renderTable($output, $allRoutes);
        }

        $io->newLine();
        $io->text(sprintf('共 <info>%d</info> 条路由', count($allRoutes)));

        return Command::SUCCESS;
    }

    /**
     * 自动发现 app/ 下的多模块控制器目录 [namespace => dir].
     *
     * @return array<string, string> */
    private function discoverModuleControllers(): array
    {
        $map        = [];
        $modulesDir = BASE_PATH . '/app/Modules';

        if (! is_dir($modulesDir)) {
            return $map;
        }

        foreach (array_diff(scandir($modulesDir), ['.', '..']) as $entry) {
            $entryPath = $modulesDir . '/' . $entry;

            if (! is_dir($entryPath)) {
                continue;
            }

            $ctrlDir = $entryPath . '/Controllers';
            if (! is_dir($ctrlDir)) {
                continue;
            }

            $map['App\\Modules\\' . $entry . '\\Controllers'] = $ctrlDir;
        }

        return $map;
    }

    /**
     * 从命名空间推导模块展示名（取第二段）.
     */
    private function moduleNameFromNamespace(string $namespace): string
    {
        $parts = explode('\\', trim($namespace, '\\'));
        return $parts[1] ?? $parts[0] ?? $namespace;
    }

    /**
     * 加载注解路由.
     *
     * @return array<mixed> */
    private function loadAnnotatedRoutes(string $controllerDir, string $namespace): array
    {
        if (! is_dir($controllerDir)) {
            return [];
        }

        $loader = new AttributeRouteLoader($controllerDir, $namespace);
        $routes = $loader->loadRoutes();

        $result = [];
        foreach ($routes->all() as $name => $route) {
            $result[$name] = $route;
        }

        return $result;
    }

    /**
     * 格式化路由信息.
     *
     * @return array<mixed> */
    private function formatRoute(string $name, Route $route, string $source): array
    {
        $methods = $route->getMethods();
        if (empty($methods)) {
            $methods = ['ANY'];
        }

        return [
            'name'       => $name,
            'path'       => $route->getPath(),
            'methods'    => implode(', ', $methods),
            'controller' => $route->getDefault('_controller') ?? '-',
            'source'     => $source,
        ];
    }

    /**
     * 筛选路由.
     *
     * @param array<mixed> $routes
     * @return array<mixed> */
    private function filterRoutes(array $routes, ?string $method, ?string $path, ?string $name): array
    {
        return array_filter($routes, function ($route) use ($method, $path, $name) {
            // 方法筛选
            if ($method && ! str_contains($route['methods'], $method)) {
                return false;
            }

            // 路径筛选
            if ($path && ! str_contains($route['path'], $path)) {
                return false;
            }

            // 名称筛选
            if ($name && ! str_contains($route['name'], $name)) {
                return false;
            }

            return true;
        });
    }

    /**
     * 渲染表格
     *
     * @param array<mixed> $routes
     */
    private function renderTable(OutputInterface $output, array $routes): void
    {
        $table = new Table($output);
        $table->setHeaders(['名称', '路径', '方法', '控制器', '来源']);
        $table->setColumnWidths([30, 40, 10, 45, 10]);

        foreach ($routes as $route) {
            $table->addRow([
                $this->truncate($route['name'], 28),
                $this->truncate($route['path'], 38),
                $route['methods'],
                $this->truncate($route['controller'], 43),
                $route['source'],
            ]);
        }

        $table->render();
    }

    /**
     * 截断字符串.
     */
    private function truncate(string $str, int $length): string
    {
        if (strlen($str) <= $length) {
            return $str;
        }
        return substr($str, 0, $length - 3) . '...';
    }
}
