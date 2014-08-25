<?php

namespace TopGames\Support\Repos;

interface RepositoryCacheableQueriesInterface
{
    public function all();

    //public function make(array $with = array());

    public function find($id, array $with = array());

    public function findOrFail($id, array $with = array()); /*

    public function firstBy(array $where = array(), array $with = array());

    public function firstOrFailBy(array $where = array(), array $with = array());

    public function getBy(array $where = array(), array $with = array());

    public function getByLimit($limit, array $where = array(), array $with = array());

    public function has($relation, array $where = array(), array $with = array());

    public function hasFirst($relation, array $where = array(), array $with = array());

    public function hasFirstOrFail($relation, array $where = array(), array $with = array());

    public function getByPage($page = 1, $limit = 10, array $where = array(), $with = array());

    public function create(array $inputs);

    public function update(array $inputs);

    public function updateById($id, array $inputs);

    public function updateBy(array $where, array $inputs);

    public function updateOrCreateBy(array $where, array $inputs);

    public function destroy($id);

    public function destroyFirstBy(array $where);

    public function truncate();

    public function count();

    public function countBy(array $where = array()); */

}

