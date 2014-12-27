<?php

namespace MicheleAngioni\Support\Repos;

interface XMLRepositoryInterface
{
    public function getFilePath();

    public function getFile();

    public function loadFile();
}