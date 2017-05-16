<?php

namespace MicheleAngioni\Support\Repos;

interface RepositoryCacheableQueriesInterface
{
    public function all();

    public function find($id, array $with = []);

    public function findOrFail($id, array $with = []);
}
