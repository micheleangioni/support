<?php

namespace TopGames\Support\Repos;

interface RepositoryCacheableQueriesInterface
{
    public function all();

    public function find($id, array $with = array());

    public function findOrFail($id, array $with = array());
}

