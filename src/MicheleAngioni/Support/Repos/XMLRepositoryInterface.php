<?php

namespace MicheleAngioni\Support\Repos;

interface XMLRepositoryInterface
{
    public function getFilePath(): string;

    public function getFile();

    public function loadFile();
}
