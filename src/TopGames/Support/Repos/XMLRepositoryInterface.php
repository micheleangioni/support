<?php

namespace TopGames\Support\Repos;

interface XMLRepositoryInterface
{
    public function getFilePath();

    public function getFile();

    public function loadFile();
}