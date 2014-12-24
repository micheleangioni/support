<?php

namespace TopGames\Support\Repos;

use Illuminate\Support\Collection;

class AbstractEloquentRepository implements RepositoryCacheableQueriesInterface
{

    public function all()
    {
        return $this->model->all();
    }

    /**
     * Make a new instance of the entity to query on
     *
     * @param array $with
     */
    public function make(array $with = array())
    {
        return $this->model->with($with);
    }


    // <--- QUERYING METHODS --->


    public function find($id, array $with = array())
    {
        $query = $this->make($with);

        return $query->find($id);
    }


    public function findOrFail($id, array $with = array())
    {
        $query = $this->make($with);

        return $query->findOrFail($id);
    }


    public function first()
    {
        $query = $this->make();

        return $query->first();
    }


    public function firstOrFail()
    {
        $query = $this->make();

        return $query->firstOrFail();
    }


    public function firstBy(array $where = array(), array $with = array())
    {
        $query = $this->make($with);

        foreach($where as $key => $value)
        {
            $query = $query->where($key, '=', $value);
        }

        return $query->first();
    }


    public function firstOrFailBy(array $where = array(), array $with = array())
    {
        $query = $this->make($with);

        foreach($where as $key => $value)
        {
            $query = $query->where($key, '=', $value);
        }

        return $query->firstOrFail();
    }


    public function getBy(array $where = array(), array $with = array())
    {
        $query = $this->make($with);

        foreach($where as $key => $value)
        {
            $query = $query->where($key, '=', $value);
        }

        return $query->get();
    }


    public function getByLimit($limit, array $where = array(), array $with = array())
    {
        $query = $this->make($with);

        foreach($where as $key => $value)
        {
            $query = $query->where($key, '=', $value);
        }

        return $query->take($limit)->get();
    }


    public function getByOrder($orderBy, array $where = array(), array $with = array(), $order = 'desc', $limit = 0)
    {
        $query = $this->make($with);

        foreach($where as $key => $value)
        {
            $query = $query->where($key, '=', $value);
        }

        $query = $query->orderBy($orderBy, $order);

        if($limit) {
            $query = $query->take($limit);
        }

        return $query->get();
    }

    /**
     * Return all results that have a required relationship
     *
     * @param  string  $relation
     * @param  array   $where
     * @param  array   $with
     * @param  int     $hasAtLeast = 1
     *
     * @return Collection
     */
    public function has($relation, array $where = array(), array $with = array(), $hasAtLeast = 1)
    {
        $query = $this->make($with);

        foreach($where as $key => $value)
        {
            $query = $query->where($key, '=', $value);
        }

        return $query->has($relation, '>=', $hasAtLeast)->get();
    }

    /**
     * Return first results that have a required relationship
     *
     * @param  string  $relation
     * @param  array   $where
     * @param  array   $with
     * @param  int     $hasAtLeast = 1
     *
     * @return Collection
     */
    public function hasFirst($relation, array $where = array(), array $with = array(), $hasAtLeast = 1)
    {
        $query = $this->make($with);

        foreach($where as $key => $value)
        {
            $query = $query->where($key, '=', $value);
        }

        return $query->has($relation, '>=', $hasAtLeast)->first();
    }

    /**
     * Return firstOrFail result that have a required relationship,
     *
     * @param  string  $relation
     * @param  array   $where
     * @param  array   $with
     * @param  int     $hasAtLeast = 1
     *
     * @return Collection
     */
    public function hasFirstOrFail($relation, array $where = array(), array $with = array(), $hasAtLeast = 1)
    {
        $query = $this->make($with);

        foreach($where as $key => $value)
        {
            $query = $query->where($key, '=', $value);
        }

        return $query->has($relation, '>=', $hasAtLeast)->firstOrFail();
    }

    /**
     * Get Results by Page.
     *
     * @param  int    $page
     * @param  int    $limit
     * @param  array  $where
     * @param  array  $with
     *
     * @return Collection
     */
    public function getByPage($page = 1, $limit = 10, array $where = array(), $with = array())
    {
        $query = $this->make($with);

        foreach($where as $key => $value)
        {
            $query = $query->where($key, '=', $value);
        }

        return $query->skip($limit * ($page - 1))
                     ->take($limit)
                     ->get();
    }


    // <--- CREATING / UPDATING / DELETING METHODS --->


    public function insert(array $collection)
    {
        foreach($collection as $key => $inputs) {
            $collection[$key] = $this->purifyInputs($inputs);
        }

        return $this->model->insert($collection);
    }


    public function create(array $inputs)
    {
        $inputs = $this->purifyInputs($inputs);

        return $this->model->create($inputs);
    }


    public function update(array $inputs)
    {
        $inputs = $this->purifyInputs($inputs);

        return $this->model->update($inputs);
    }


    public function updateById($id, array $inputs)
    {
        $inputs = $this->purifyInputs($inputs);

        $model = $this->model->findOrFail($id);
        return $model->update($inputs);
    }


    public function updateBy(array $where, array $inputs)
    {
        $inputs = $this->purifyInputs($inputs);

        $query = $this->make();

        foreach($where as $key => $value)
        {
            $query = $query->where($key, '=', $value);
        }

        return $query->update($inputs);
    }


    public function updateOrCreateBy(array $where, array $inputs)
    {
        $inputs = $this->purifyInputs($inputs);

        $query = $this->make();

        foreach($where as $key => $value)
        {
            $query = $query->where($key, '=', $value);
        }

        $model = $query->first();

        if($model) {
            $model->update($inputs);
        }
        else {
            return $this->model->create($inputs);
        }
    }


    public function destroy($id)
    {
        $model = $this->model->findOrFail($id);
        return $model->delete();
    }


    public function destroyFirstBy(array $where)
    {
        $where = $this->purifyInputs($where);

        $model = $this->firstOrFailBy($where);
        return $model->delete();
    }


    public function truncate()
    {
        return $this->model->truncate();
    }


    // <--- COUNT METHODS --->


    public function count()
    {
        return $this->model->count();
    }


    public function countBy(array $where = array())
    {
        $query = $this->make();

        foreach($where as $key => $value)
        {
            $query = $query->where($key, '=', $value);
        }

        return $query->count();
    }


    // <--- INTERNALLY USED METHODS --->


    /**
     * Remove keys from the $inputs array beginning with '_' .
     *
     * @param  array  $inputs
     * @return array
     */
    protected function purifyInputs(array $inputs)
    {
        foreach($inputs as $key => $input) {
            if ($key[0] === '_') {
                unset($inputs[$key]);
            }
        }

        return $inputs;
    }

}