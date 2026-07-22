<?php

declare(strict_types=1);

/**
 * @Developer: ck
 * @Email: ck@eqray.com
 */

namespace Framework\Repository;

use Framework\Repository\Exceptions\DatabaseException;

interface RepositoryInterface
{
    /**
     * 根据ID查找记录.
     * @param  array<mixed>      $with
     * @throws DatabaseException
     */
    public function findById(int|string $id, array $with = []): mixed;

    /**
     * 根据条件查找单条记录.
     * @param  array<mixed>      $criteria
     * @param  array<mixed>      $with
     * @throws DatabaseException
     */
    public function findOneBy(array $criteria, array $with = []): mixed;

    /**
     * 根据条件查找多条记录.
     * @param  array<mixed>      $criteria
     * @param  array<mixed>      $orderBy
     * @param  array<mixed>      $with
     * @throws DatabaseException
     */
    public function findAll(array $criteria = [], array $orderBy = [], ?int $limit = null, array $with = []): mixed;

    /**
     * 分页查询.
     * @param  array<mixed>      $criteria
     * @param  array<mixed>      $orderBy
     * @param  array<mixed>      $with
     * @throws DatabaseException
     */
    public function paginate(array $criteria = [], int $perPage = 15, array $orderBy = [], array $with = []): mixed;

    /**
     * 创建记录.
     * @param  array<mixed>      $data
     * @throws DatabaseException
     */
    public function create(array $data): mixed;

    /**
     * 更新记录.
     * @param  array<mixed>      $criteria
     * @param  array<mixed>      $data
     * @throws DatabaseException
     */
    public function update(array $criteria, array $data): bool;

    /**
     * 按条件批量更新.
     * @param  array<mixed>      $criteria
     * @param  array<mixed>      $data
     * @throws DatabaseException
     */
    public function updateBy(array $criteria, array $data): int;

    /**
     * 删除记录.
     * @param  array<mixed>      $criteria
     * @throws DatabaseException
     */
    public function delete(array $criteria): bool;

    /**
     * 按条件批量删除.
     * @param  array<mixed>      $criteria
     * @throws DatabaseException
     */
    public function deleteBy(array $criteria): int;

    /**
     * 自增操作.
     * @param  array<mixed>      $criteria
     * @param  array<mixed>      $extra
     * @throws DatabaseException
     */
    public function increment(array $criteria, string $field, int $amount = 1, array $extra = []): bool;

    /**
     * 自减操作.
     * @param  array<mixed>      $criteria
     * @param  array<mixed>      $extra
     * @throws DatabaseException
     */
    public function decrement(array $criteria, string $field, int $amount = 1, array $extra = []): bool;

    /**
     * 聚合查询.
     * @param  array<mixed>      $criteria
     * @throws DatabaseException
     */
    public function aggregate(string $type, array $criteria = [], string $field = '*'): float|int|string;

    /**
     * 事务处理.
     */
    public function transaction(\Closure $callback): mixed;

    /**
     * 原生查询.
     * @param  array<mixed>      $bindings
     * @return array<mixed>
     * @throws DatabaseException
     */
    public function query(string $sql, array $bindings = []): array;

    /**
     * 原生执行.
     * @param  array<mixed>      $bindings
     * @throws DatabaseException
     */
    public function execute(string $sql, array $bindings = []): int;
}
