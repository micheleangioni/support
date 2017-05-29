<?php

namespace MicheleAngioni\Support\Repos;

use Illuminate\Support\Collection;

interface RepositoryInterface
{
    /**
     * Return all records.
     *
     * @param array $with
     *
     * @return Collection
     */
    public function all(array $with = []);

    /**
     * Make a new instance of the entity to query on.
     *
     * @param array $with
     */
    public function make(array $with = []);

    /**
     * Find a specific record.
     * Return null if not found.
     *
     * @param int $id
     * @param array $with
     *
     * @return mixed
     */
    public function find($id, array $with = []);

    /**
     * Find a specific record.
     * Throws exception if not found.
     *
     * @param $id
     * @param array $with
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return mixed
     */
    public function findOrFail($id, array $with = []);

    /**
     * Return the first record of the table.
     * Return null if no record is found.
     *
     * @return mixed
     */
    public function first();

    /**
     * Return the first record.
     * Throws exception if no record is found.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @return mixed
     */
    public function firstOrFail();

    /**
     * Return the first record querying input parameters.
     * Return null if no record is found.
     *
     * @param array $where
     * @param array $with
     *
     * @return mixed
     */
    public function firstBy(array $where = [], array $with = []);

    /**
     * Return the first record querying input parameters.
     * Throws exception if no record is found.
     *
     * @param array $where
     * @param array $with
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return mixed
     */
    public function firstOrFailBy(array $where = [], array $with = []);

    /**
     * Return records querying input parameters.
     *
     * @param array $where
     * @param array $with
     *
     * @return Collection
     */
    public function getBy(array $where = [], array $with = []);

    /**
     * Return the first $limit records querying input parameters.
     *
     * @param int $limit
     * @param array $where
     * @param array $with
     *
     * @return Collection
     */
    public function getByLimit($limit, array $where = [], array $with = []);

    /**
     * Return the first ordered $limit records querying input parameters.
     * $limit = 0 means no limits.
     *
     * @param $orderBy
     * @param array $where
     * @param array $with
     * @param string $order
     * @param int $limit
     *
     * @return Collection
     */
    public function getByOrder($orderBy, array $where = [], array $with = [], $order = 'desc', $limit = 0);

    /**
     * Return the first ordered $limit records querying input parameters.
     * $limit = 0 means no limits.
     *
     * @param string|int $whereInKey
     * @param array $whereIn
     * @param array $with
     * @param string|null $orderBy
     * @param string $order
     * @param int $limit
     *
     * @return Collection
     */
    public function getIn($whereInKey, array $whereIn = [], $with = [], $orderBy = null, $order = 'desc', $limit = 0);

    /**
     * Return the first ordered $limit records querying input parameters.
     * $limit = 0 means no limits.
     *
     * @param string|int $whereNotInKey
     * @param array $whereNotIn
     * @param array $with
     * @param string|null $orderBy
     * @param string $order
     * @param int $limit
     *
     * @return Collection
     */
    public function getNotIn($whereNotInKey, array $whereNotIn = [], $with = [], $orderBy = null, $order = 'desc', $limit = 0);

    /**
     * Return all results that have a required relationship.
     *
     * @param  string $relation
     * @param  array $where
     * @param  array $with
     * @param  int $hasAtLeast = 1
     *
     * @return Collection
     */
    public function getHas($relation, array $where = [], array $with = [], $hasAtLeast = 1);

    /**
     * Return the first result that has a required relationship.
     * Return null if no record is found.
     *
     * @param  string $relation
     * @param  array $where
     * @param  array $with
     * @param  int $hasAtLeast = 1
     *
     * @return Collection
     */
    public function hasFirst($relation, array $where = [], array $with = [], $hasAtLeast = 1);

    /**
     * Return the first result that have a required relationship.
     * Throws exception if no record is found.
     *
     * @param  string $relation
     * @param  array $where
     * @param  array $with
     * @param  int $hasAtLeast = 1
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return Collection
     */
    public function hasFirstOrFail($relation, array $where = [], array $with = [], $hasAtLeast = 1);

    /**
     * Return all results that have a required relationship with input constraints.
     *
     * @param  string $relation
     * @param  array $where
     * @param  array $whereHas
     * @param  array $with
     *
     * @return Collection
     */
    public function whereHas($relation, array $where = [], array $whereHas = [], array $with = []);

    /**
     * Get ordered results by Page.
     *
     * @param  int $page
     * @param  int $limit
     * @param  array $where
     * @param  array $with
     * @param  string|null $orderBy
     * @param  string $order
     *
     * @return Collection
     */
    public function getByPage($page = 1, $limit = 10, array $where = [], $with = [], $orderBy = null, $order = 'desc');

    /**
     * Create a collection of new records.
     *
     * @param array $collection
     *
     * @return mixed
     */
    public function insert(array $collection);

    /**
     * Create a new record.
     *
     * @param array $inputs
     *
     * @return mixed
     */
    public function create(array $inputs = []);

    /**
     * Update all records.
     *
     * @param array $inputs
     *
     * @return mixed
     */
    public function update(array $inputs);

    /**
     * Update an existing record, retrieved by id.
     *
     * @param int $id
     * @param array $inputs
     *
     * @return mixed
     */
    public function updateById($id, array $inputs);

    /**
     * Update all records matching input parameters.
     *
     * @param array $where
     * @param array $inputs
     *
     * @return mixed
     */
    public function updateBy(array $where, array $inputs);

    /**
     * Update the record matching input parameters.
     * If no record is found, create a new one.
     *
     * @param array $where
     * @param array $inputs
     *
     * @return mixed
     */
    public function updateOrCreateBy(array $where, array $inputs = []);

    /**
     * Delete input record.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function destroy($id);

    /**
     * Retrieve and delete the first record matching input parameters.
     * Throws exception if no record is found.
     *
     * @param array $where
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return mixed
     */
    public function destroyFirstBy(array $where);

    /**
     * Retrieve and delete the all records matching input parameters.
     *
     * @param array $where
     *
     * @return mixed
     */
    public function destroyBy(array $where);

    /**
     * Truncate the table.
     *
     * @return mixed
     */
    public function truncate();

    /**
     * Count the number of records.
     *
     * @return int
     */
    public function count();

    /**
     * Count the number of records matching input parameters.
     *
     * @return int
     */
    public function countBy(array $where = []);

    /**
     * Count all records that have a required relationship and matching input parameters..
     *
     * @param  string $relation
     * @param  array $where
     * @param  array $whereHas
     *
     * @return int
     */
    public function countWhereHas($relation, array $where = [], array $whereHas = []);
}
