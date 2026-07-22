<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Repository\Strategies;

use think\facade\Db;

// ThinkPHP策略实现
class ThinkStrategy implements OrmStrategyInterface
{
    public function getQueryBuilder(string $modelClass): mixed
    {
        if (class_exists($modelClass)) {
            return (new $modelClass())->db();
        }
        return Db::table($modelClass);
    }

    /**
     * @param array<mixed> $extra
     */
    public function increment(mixed $query, string $field, int $amount, array $extra): bool
    {
        return (bool) $query->inc($field, $amount)->update($extra);
    }

    /**
     * @param array<mixed> $extra
     */
    public function decrement(mixed $query, string $field, int $amount, array $extra): bool
    {
        return (bool) $query->dec($field, $amount)->update($extra);
    }

    public function transaction(\Closure $callback): mixed
    {
        return Db::transaction($callback);
    }

    /**
     * @param  array<mixed> $bindings
     * @return array<mixed>
     */
    public function query(string $sql, array $bindings): array
    {
        return Db::query($sql, $bindings);
    }

    /**
     * @param array<mixed> $bindings
     */
    public function execute(string $sql, array $bindings): int
    {
        return (int) Db::execute($sql, $bindings);
    }
}
