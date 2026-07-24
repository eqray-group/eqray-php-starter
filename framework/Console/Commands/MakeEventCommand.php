<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * 创建事件命令.
 *
 * 该命令用于通过命令行快速创建新的事件类文件。
 * 自动生成实现 EventInterface 接口的事件类模板。
 *
 * 使用方法：
 *   php console make:event UserRegistered
 */
class MakeEventCommand extends Command
{
    /**
     * 命令名称.
     *
     * @var string
     */
    protected static $defaultName = 'make:event';

    /**
     * 命令描述.
     *
     * @var string
     */
    protected static $defaultDescription = '创建一个新的事件类';

    /**
     * 配置命令参数和选项.
     *
     * 设置命令名称、描述以及必需的事件名称参数。
     */
    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription(self::$defaultDescription)
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                '事件类名称（例如：UserRegistered 会生成 UserRegisteredEvent）'
            )
            ->addOption(
                'module',
                'm',
                InputOption::VALUE_OPTIONAL,
                '所属模块（不填则为公共 app/Events），例如 --module=User 生成 app/Modules/User/Events'
            );
    }

    /**
     * 执行命令.
     *
     * 创建事件文件，包含以下步骤：
     * 1. 解析事件名称并构建文件路径
     * 2. 检查文件是否已存在
     * 3. 创建目标目录（如不存在）
     * 4. 生成事件类内容并写入文件
     *
     * @param InputInterface  $input  输入接口，用于获取命令参数
     * @param OutputInterface $output 输出接口，用于输出结果信息
     *
     * @return int 命令执行状态码（Command::SUCCESS 或 Command::FAILURE）
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $eventBaseName = $input->getArgument('name');
        $eventName     = ucfirst($eventBaseName) . 'Event';
        $module        = $input->getOption('module');
        if ($module !== null && $module !== '') {
            $module    = ucfirst((string) $module);
            $namespace = 'App\\Modules\\' . $module . '\\Events';
            $directory = __DIR__ . '/../../../app/Modules/' . $module . '/Events';
        } else {
            $namespace = 'App\\Events';
            $directory = __DIR__ . '/../../../app/Events';
        }
        $filePath = $directory . '/' . $eventName . '.php';

        if (file_exists($filePath)) {
            $output->writeln("<error>错误：事件 {$eventName} 已存在于 {$filePath}</error>");
            return Command::FAILURE;
        }

        $filesystem = new Filesystem();

        try {
            $filesystem->mkdir($directory);

            $content = $this->generateEventContent($eventName, $namespace);

            $filesystem->dumpFile($filePath, $content);

            $output->writeln('<info>成功生成事件类：</info>');
            $output->writeln("路径：{$filePath}");
            return Command::SUCCESS;
        } catch (IOExceptionInterface $e) {
            $output->writeln('<error>文件操作错误：' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        } catch (\Exception $e) {
            $output->writeln('<error>发生错误：' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }

    /**
     * 生成事件类内容.
     *
     * 创建实现 EventInterface 接口的事件类模板。
     * 模板包含基础构造函数，可根据需要添加事件数据属性。
     *
     * @param string $eventName 事件类名
     * @param string $namespace 事件命名空间
     *
     * @return string 生成的事件 PHP 代码
     */
    private function generateEventContent(string $eventName, string $namespace): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace {$namespace};

use Framework\\Event\\EventInterface;

class {$eventName} implements EventInterface
{
    /**
     * 构造函数 - 可根据需要添加事件数据
     */
    public function __construct(
        // public readonly mixed \$data,
    ) {
    }
}
PHP;
    }
}
