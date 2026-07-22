<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Database;

/**
 * ORM 模型工厂接口.
 *
 * @method mixed              getSchemaBuilder()
 * @method bool               statement(string $sql, array<int|string, mixed> $bindings = [])
 * @method array<int, object> select(string $sql, array<int|string, mixed> $bindings = [])
 * @method mixed              table(string $table)
 */
interface DatabaseInterface
{
    /**
     * 像函数一样调用工厂 (语法糖).
     */
    public function __invoke(string $modelClass): mixed;

    /**
     * 创建模型实例或查询构造器.
     */
    public function make(string $modelClass): mixed;
}
