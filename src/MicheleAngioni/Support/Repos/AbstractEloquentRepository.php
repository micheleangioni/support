<?php

namespace MicheleAngioni\Support\Repos;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AbstractEloquentRepository implements RepositoryCacheableQueriesInterface, RepositoryInterface
{
    /**
     * Return all records.
     *
     * @param array $with
     *
     * @return Collection
     */
    public function all(array $with = [])
    {
        $query = $this->make($with);

        return $query->get();
    }

    /**
     * Make a new instance of the entity to query on.
     *
     * @param array $with
     */
    public function make(array $with = [])
    {
        return $this->model->with($with);
    }


    // <--- QUERYING METHODS --->

    /**
     * Find a specific record.
     * Return null if not found.
     *
     * @param int $id
     * @param array $with
     *
     * @return mixed
     */
    public function find($id, array $with = [])
    {
        $query = $this->make($with);

        return $query->find($id);
    }

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
    public function findOrFail($id, array $with = [])
    {
        $query = $this->make($with);

        return $query->findOrFail($id);
    }

    /**
     * Return the first record of the table.
     * Return null if no record is found.
     *
     * @return mixed
     */
    public function first()
    {
        $query = $this->make();

        return $query->first();
    }

    /**
     * Return the first record.
     * Throws exception if no record is found.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @return mixed
     */
    public function firstOrFail()
    {
        $query = $this->make();

        return $query->firstOrFail();
    }

    /**
     * Return the first record querying input parameters.
     * Return null if no record is found.
     *
     * @param array $where
     * @param array $with
     *
     * @return mixed
     */
    public function firstBy(array $where = [], array $with = [])
    {
        $query = $this->make($with);

        $query = $this->applyWhere($query, $where);

        return $query->first();
    }

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
    public function firstOrFailBy(array $where = [], array $with = [])
    {
        $query = $this->make($with);

        $query = $this->applyWhere($query, $where);

        return $query->firstOrFail();
    }

    /**
     * Return records querying input parameters.
     *
     * @param array $where
     * @param array $with
     *
     * @return Collection
     */
    public function getBy(array $where = [], array $with = [])
    {
        $query = $this->make($with);

        $query = $this->applyWhere($query, $where);

        return $query->get();
    }

    /**
     * Return the first $limit records querying input parameters.
     *
     * @param int $limit
     * @param array $where
     * @param array $with
     *
     * @return Collection
     */
    public function getByLimit($limit, array $where = [], array $with = [])
    {
        $query = $this->make($with);

        $query = $this->applyWhere($query, $where);

        return $query->take($limit)->get();
    }

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
    public function getByOrder($orderBy, array $where = [], array $with = [], $order = 'desc', $limit = 0)
    {
        $query = $this->make($with);

        $query = $this->applyWhere($query, $where);

        $query = $query->orderBy($orderBy, $order);

        if ($limit) {
            $query = $query->take($limit);
        }

        return $query->get();
    }

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
    public function getIn($whereInKey, array $whereIn = [], $with = [], $orderBy = null, $order = 'desc', $limit = 0)
    {
        $query = $this->make($with);

        $query = $query->whereIn($whereInKey, $whereIn);

        if ($orderBy) {
            $query = $query->orderBy($orderBy, $order);
        }

        if ($limit) {
            $query = $query->take($limit);
        }

        return $query->get();
    }

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
    public function getNotIn(
        $whereNotInKey,
        array $whereNotIn = [],
        $with = [],
        $orderBy = null,
        $order = 'desc',
        $limit = 0
    ) {
        $query = $this->make($with);

        $query = $query->whereNotIn($whereNotInKey, $whereNotIn);

        if ($orderBy) {
            $query = $query->orderBy($orderBy, $order);
        }

        if ($limit) {
            $query = $query->take($limit);
        }

        return $query->get();
    }

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
    public function getHas($relation, array $where = [], array $with = [], $hasAtLeast = 1)
    {
        $query = $this->make($with);

        $query = $this->applyWhere($query, $where);

        return $query->has($relation, '>=', $hasAtLeast)->get();
    }

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
    public function hasFirst($relation, array $where = [], array $with = [], $hasAtLeast = 1)
    {
        $query = $this->make($with);

        $query = $this->applyWhere($query, $where);

        return $query->has($relation, '>=', $hasAtLeast)->first();
    }

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
    public function hasFirstOrFail($relation, array $where = [], array $with = [], $hasAtLeast = 1)
    {
        $query = $this->make($with);

        $query = $this->applyWhere($query, $where);

        return $query->has($relation, '>=', $hasAtLeast)->firstOrFail();
    }

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
    public function whereHas($relation, array $where = [], array $whereHas = [], array $with = [])
    {
        $query = $this->make($with);

        $query = $this->applyWhere($query, $where);

        $query = $query->whereHas($relation, function ($q) use ($whereHas) {
            $this->applyWhere($q, $whereHas);
        });

        return $query->get();
    }

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
    public function getByPage($page = 1, $limit = 10, array $where = [], $with = [], $orderBy = null, $order = 'desc')
    {
        $query = $this->make($with);

        $query = $this->applyWhere($query, $where);

        if ($orderBy) {
            $query = $query->orderBy($orderBy, $order);
        }

        return $query->skip($limit * ($page - 1))
            ->take($limit)
            ->get();
    }


    // <--- CREATING / UPDATING / DELETING METHODS --->

    /**
     * Create a collection of new records.
     *
     * @param array $collection
     *
     * @return mixed
     */
    public function insert(array $collection)
    {
        foreach ($collection as $key => $inputs) {
            $collection[$key] = $this->purifyInputs($inputs);
        }

        return $this->model->insert($collection);
    }

    /**
     * Create a new record.
     *
     * @param array $inputs
     *
     * @return mixed
     */
    public function create(array $inputs = [])
    {
        $inputs = $this->purifyInputs($inputs);

        return $this->model->create($inputs);
    }

    /**
     * Update all records.
     *
     * @param array $inputs
     *
     * @return mixed
     */
    public function update(array $inputs)
    {
        $inputs = $this->purifyInputs($inputs);

        return $this->model->update($inputs);
    }

    /**
     * Update an existing record, retrieved by id.
     *
     * @param int $id
     * @param array $inputs
     *
     * @return mixed
     */
    public function updateById($id, array $inputs)
    {
        $inputs = $this->purifyInputs($inputs);

        $model = $this->model->findOrFail($id);

        return $model->update($inputs);
    }

    /**
     * Update all records matching input parameters.
     *
     * @param array $where
     * @param array $inputs
     *
     * @return mixed
     */
    public function updateBy(array $where, array $inputs)
    {
        $inputs = $this->purifyInputs($inputs);

        $query = $this->make();

        $query = $this->applyWhere($query, $where);

        return $query->update($inputs);
    }

    /**
     * Update the record matching input parameters.
     * If no record is found, create a new one.
     *
     * @param array $where
     * @param array $inputs
     *
     * @return mixed
     */
    public function updateOrCreateBy(array $where, array $inputs = [])
    {
        $inputs = $this->purifyInputs($inputs);

        $query = $this->make();

        $query = $this->applyWhere($query, $where);

        $model = $query->first();

        if ($model) {
            $model->update($inputs);

            return $model;
        } else {
            return $this->model->create($inputs);
        }
    }

    /**
     * Delete input record.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function destroy($id)
    {
        $model = $this->model->findOrFail($id);

        return $model->delete();
    }

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
    public function destroyFirstBy(array $where)
    {
        $model = $this->firstOrFailBy($where);

        return $model->delete();
    }

    /**
     * Retrieve and delete the all records matching input parameters.
     *
     * @param array $where
     *
     * @return mixed
     */
    public function destroyBy(array $where)
    {
        $query = $this->make();

        $query = $this->applyWhere($query, $where);

        return $query->delete();
    }

    /**
     * Truncate the table.
     *
     * @return mixed
     */
    public function truncate()
    {
        return $this->model->truncate();
    }


    // <--- COUNT METHODS --->

    /**
     * Count the number of records.
     *
     * @return int
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * Count the number of records matching input parameters.
     *
     * @return int
     */
    public function countBy(array $where = [])
    {
        $query = $this->make();

        $query = $this->applyWhere($query, $where);

        return $query->count();
    }

    /**
     * Count all records that have a required relationship and matching input parameters..
     *
     * @param  string $relation
     * @param  array $where
     * @param  array $whereHas
     *
     * @return int
     */
    public function countWhereHas($relation, array $where = [], array $whereHas = [])
    {
        $query = $this->make();

        $query = $this->applyWhere($query, $where);

        $query = $query->whereHas($relation, function ($q) use ($whereHas) {
            $this->applyWhere($q, $whereHas);
        });

        return $query->count();
    }


    // <--- INTERNALLY USED METHODS --->

    /**
     * Apply the where clauses to input query.
     * $where can have the format ['key' => 'value'] or ['key' => [<operator>, 'value']]
     *
     * @param  Builder $query
     * @param  array $where
     *
     * @return Builder
     */
    protected function applyWhere(Builder $query, array $where)
    {
        foreach ($where as $key => $value) {
            if (is_null($value)) {
                $query = $query->whereNull($key);
            } else {
                if (is_array($value)) {
                    $query = $query->where($key, $value[0], $value[1]);
                } else {
                    $query = $query->where($key, '=', $value);
                }
            }
        }

        return $query;
    }

    /**
     * Remove keys from the $inputs array beginning with '_' .
     *
     * @param  array $inputs
     *
     * @return array
     */
    protected function purifyInputs(array $inputs)
    {
        foreach ($inputs as $key => $input) {
            if ($key[0] === '_') {
                unset($inputs[$key]);
            }
        }

        return $inputs;
    }
}
