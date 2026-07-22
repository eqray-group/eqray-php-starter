<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Basic;

use Framework\DI\Injectable;
use Framework\ORM\Trait\ServicesTrait;
use Illuminate\Database\Capsule\Manager;

/**
 * BaseService - 泛型服务基类.
 *
 * 提供以下核心功能：
 * - DAO 注入与代理
 * - 框架无关的事务处理
 * - 分页参数规范化
 *
 * 子类可指定具体 DAO 类型：
 * @template T of BaseDao
 * @method getModel()
 */
abstract class BaseService
{
    use ServicesTrait;
    use Injectable;

    /**
     * 模型注入：使用泛型类型.
     * @var ?T
     */
    protected ?BaseDao $dao = null;

    /**
     * 当前使用的事务连接
     * 用于支持嵌套事务和连接复用.
     * @var null|mixed
     */
    protected mixed $transactionConnection = null;

    /**
     * 事务嵌套级别.
     */
    protected int $transactionLevel = 0;

    /**
     * 构造函数.
     *
     * 初始化依赖注入和服务。
     */
    public function __construct()
    {
        $db = app('db');
        $this->inject();
        $this->initialize();
    }

    // =========================================================================
    //  方法代理
    // =========================================================================

    /**
     * 代理 DAO 调用.
     *
     * 当调用不存在的方法时，自动转发给 DAO 处理。
     *
     * @param  string                  $name      方法名
     * @param  array<mixed>            $arguments 方法参数
     * @return mixed                   方法返回值
     * @throws \RuntimeException       DAO 未初始化时抛出
     * @throws \BadMethodCallException 方法不存在时抛出
     */
    public function __call(string $name, array $arguments): mixed
    {
        if (! $this->dao) {
            throw new \RuntimeException('BaseService: DAO not initialized in service.');
        }

        try {
            // 支持返回引用调用语法，也支持 PHP 8 call
            return $this->dao->{$name}(...$arguments);
        } catch (\BadMethodCallException $e) {
            // DAO 及其适配器都不支持该方法
            throw new \BadMethodCallException(
                "BaseService: Method {$name} not found in DAO adapter (" . get_class($this->dao) . ' / adapter: ' . get_class($this->dao->getAdapter()) . '): ' . $e->getMessage()
            );
        } catch (\Throwable $e) {
            // 其它异常（例如 ORM 内部抛出的），直接向上抛或包装
            throw $e;
        }
    }

    // =========================================================================
    //  事务处理
    // =========================================================================

    /**
     * 执行事务
     *
     * @param  \Closure $closure 事务内执行的闭包
     * @param  bool     $isTran  是否启用事务（默认 true）
     * @return mixed    闭包返回值
     */
    public function transaction(\Closure $closure, bool $isTran = true): mixed
    {
        if (! $isTran) {
            return $closure();
        }

        return Manager::connection()->transaction(function () use ($closure) {
            return $closure();
        });
    }

    /**
     * 手动开始事务
     */
    public function beginTransaction(): void
    {
        if ($this->transactionLevel === 0) {
            Manager::connection()->beginTransaction();
        }

        ++$this->transactionLevel;
    }

    /**
     * 提交事务
     */
    public function commit(): void
    {
        $this->transactionLevel = max(0, $this->transactionLevel - 1);

        if ($this->transactionLevel === 0) {
            Manager::connection()->commit();
        }
    }

    /**
     * 回滚事务
     */
    public function rollback(): void
    {
        $this->transactionLevel = max(0, $this->transactionLevel - 1);

        if ($this->transactionLevel === 0) {
            Manager::connection()->rollBack();
        }
    }

    /**
     * 使用 try-catch-finally 模式执行事务
     */
    public function transactionWithTry(\Closure $closure): mixed
    {
        $this->beginTransaction();

        try {
            $result = $closure();
            $this->commit();
            return $result;
        } catch (\Throwable $e) {
            $this->rollback();
            throw $e;
        }
    }

    // =========================================================================
    //  DAO 管理
    // =========================================================================

    /**
     * 设置 DAO 实例.
     *
     * @param BaseDao $dao DAO 实例
     */
    public function setDao(BaseDao $dao): void
    {
        $this->dao = $dao;
    }

    /**
     * 获取 DAO 实例.
     *
     * @return null|BaseDao DAO 实例
     */
    public function getDao(): ?BaseDao
    {
        return $this->dao;
    }

    /**
     * 子类初始化钩子.
     *
     * 子类可根据需要覆盖此方法进行初始化操作。
     */
    protected function initialize(): void {}

    /**
     * 执行 Laravel ORM 事务
     */
    protected function executeLaravelOrmTransaction(\Closure $closure): mixed
    {
        return Manager::connection()->transaction(function () use ($closure) {
            return $closure();
        });
    }

    // =========================================================================
    //  分页处理
    // =========================================================================

    /**
     * 规范化分页参数.
     *
     * 从数组或请求中获取分页参数，支持多种输入格式。
     *
     * @param  null|array<mixed> $params       分页参数，支持以下格式：
     *                                         - null: 使用默认值
     *                                         - 关联数组: ['page' => 1, 'limit' => 10] 或 ['p' => 1, 'per_page' => 10]
     *                                         - 索引数组: [0 => page, 1 => limit]
     * @param  int               $defaultLimit 默认每页条数
     * @return array<mixed>      [page, limit, offset]
     *
     * @example
     * // 无参数
     * [$page, $limit, $offset] = $this->PageParams(null);
     *
     * // 关联数组
     * [$page, $limit, $offset] = $this->PageParams(['page' => 2, 'limit' => 20]);
     *
     * // 索引数组
     * [$page, $limit, $offset] = $this->PageParams([2, 20]);
     */
    protected function PageParams(?array $params = null, int $defaultLimit = 10): array
    {
        $page  = 1;
        $limit = $defaultLimit;

        if ($params === null) {
            return [$page, $limit, 0];
        }

        // 支持关联数组或索引数组
        if (array_is_list($params)) {
            $page  = (int) ($params[0] ?? 1);
            $limit = (int) ($params[1] ?? $defaultLimit);
        } else {
            $page  = (int) ($params['page'] ?? $params['p'] ?? 1);
            $limit = (int) ($params['limit'] ?? $params['per_page'] ?? $defaultLimit);
        }

        // 边界保护
        $page  = max(1, $page);
        $limit = max(1, min($limit, 1000)); // 限制最大 1000 条

        $offset = ($page - 1) * $limit;
        return [$page, $limit, $offset];
    }

    /**
     * 计算分页偏移量.
     *
     * @param  int $page  页码
     * @param  int $limit 每页条数
     * @return int 偏移量
     */
    protected function calculateOffset(int $page, int $limit): int
    {
        return max(0, ($page - 1) * $limit);
    }

    /**
     * 构建分页结果.
     *
     * @param  array<mixed> $items 数据列表
     * @param  int          $total 总记录数
     * @param  int          $page  当前页码
     * @param  int          $limit 每页条数
     * @return array<mixed> 分页结果
     */
    protected function buildPaginateResult(array $items, int $total, int $page, int $limit): array
    {
        return [
            'items' => $items,
            'total' => $total,
            'page'  => $page,
            'limit' => $limit,
            'pages' => (int) ceil($total / $limit),
        ];
    }
}
