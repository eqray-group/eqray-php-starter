<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * 创建模型命令.
 *
 * 该命令用于通过命令行快速创建新的模型类文件。
 * 自动生成基于 ThinkPHP Model 的模型模板，包含常用配置项。
 * 表名会根据类名自动转换为下划线复数形式。
 *
 * 使用方法：
 *   php console make:model User
 */
class MakeModelCommand extends Command
{
    /**
     * 命令名称.
     *
     * @var string
     */
    protected static $defaultName = 'make:model';

    /**
     * 命令描述.
     *
     * @var string
     */
    protected static $defaultDescription = '创建一个新的模型类';

    /**
     * 配置命令参数和选项.
     *
     * 设置命令名称、描述以及必需的模型名称参数。
     */
    protected function configure(): void
    {
        $this
            // 命令名称（冗余定义，确保兼容性）
            ->setName(self::$defaultName)
            // 命令描述
            ->setDescription(self::$defaultDescription)
            // 添加模型名称参数
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                '模型名称（例如：User 会生成 User 模型）'
            )
            // 模块选项：生成的模型归属 app/Modules/<module>/Models
            ->addOption(
                'module',
                'm',
                InputOption::VALUE_OPTIONAL,
                '所属模块（默认 User），例如 --module=Content 生成 app/Modules/Content/Models'
            );
    }

    /**
     * 执行命令.
     *
     * 创建模型文件，包含以下步骤：
     * 1. 解析模型名称并构建文件路径
     * 2. 检查文件是否已存在
     * 3. 创建目标目录（如不存在）
     * 4. 生成模型内容并写入文件
     *
     * @param InputInterface  $input  输入接口，用于获取命令参数
     * @param OutputInterface $output 输出接口，用于输出结果信息
     *
     * @return int 命令执行状态码（Command::SUCCESS 或 Command::FAILURE）
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $modelName = $input->getArgument('name');
        $className = ucfirst($modelName);
        $module    = ucfirst((string) ($input->getOption('module') ?: 'User'));
        $namespace = 'App\\Modules\\' . $module . '\\Models';
        $directory = __DIR__ . '/../../../app/Modules/' . $module . '/Models';
        $filePath  = $directory . '/' . $className . '.php';

        // 检查文件是否已存在
        if (file_exists($filePath)) {
            $output->writeln("<error>错误：模型 {$className} 已存在于 {$filePath}</error>");
            return Command::FAILURE;
        }

        // 创建文件系统实例
        $filesystem = new Filesystem();

        try {
            // 确保目录存在
            $filesystem->mkdir($directory);

            // 生成模型内容
            $content = $this->generateModelContent($className, $namespace);

            // 写入文件
            $filesystem->dumpFile($filePath, $content);

            $output->writeln('<info>成功生成模型：</info>');
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
     * 生成模型类内容.
     *
     * 创建基于 ThinkPHP Model 的模型类模板。
     * 自动将驼峰类名转换为下划线复数形式的表名。
     * 例如：UserOrder -> user_orders
     *
     * @param string $className 模型类名
     * @param string $namespace 模型命名空间
     *
     * @return string 生成的模型 PHP 代码
     */
    private function generateModelContent(string $className, string $namespace): string
    {
        // 自动生成表名（类名转小写复数形式）
        $tableName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className)) . 's';

        return <<<PHP
<?php

namespace {$namespace};

use Framework\\Basic\\BaseLaORMModel;

class {$className} extends BaseLaORMModel
{
    protected \$table = '{$tableName}';

    protected \$fillable = [];

    protected \$hidden = [];
}
PHP;
    }
}
