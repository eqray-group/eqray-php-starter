<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Repository\Strategies;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Facades\DB;

// Laravel Eloquent策略实现
class EloquentStrategy implements OrmStrategyInterface
{
    public function getQueryBuilder(string $modelClass): mixed
    {
        if (class_exists($modelClass)) {
            return (new $modelClass())->newQuery();
        }
        return Manager::table($modelClass);
    }

    /**
     * @param array<mixed> $extra
     */
    public function increment(mixed $query, string $field, int $amount, array $extra): bool
    {
        return (bool) $query->increment($field, $amount, $extra);
    }

    /**
     * @param array<mixed> $extra
     */
    public function decrement(mixed $query, string $field, int $amount, array $extra): bool
    {
        return (bool) $query->decrement($field, $amount, $extra);
    }

    public function transaction(\Closure $callback): mixed
    {
        return DB::transaction($callback);
    }

    /**
     * @param  array<int|string, mixed> $bindings
     * @return array<int, mixed>
     */
    public function query(string $sql, array $bindings): array
    {
        $result = DB::select($sql, $bindings);
        return array_map(fn ($item) => (array) $item, $result);
    }

    /**
     * @param array<int|string, mixed> $bindings
     */
    public function execute(string $sql, array $bindings): int
    {
        return DB::affectingStatement($sql, $bindings);
    }
}
