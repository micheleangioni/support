<?php

require __DIR__ . '/vendor/autoload.php';

use Sami\Sami;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in($dir = __DIR__ . '/src');

$versions = GitVersionCollection::create($dir)
    ->add('master', 'master branch')
    ->addFromTags('v1.*');

$options = array(
    'versions' => $versions,
    'title' => 'Support API',
    'build_dir' => __DIR__ . '/build/docs/%version%',
    'cache_dir' => __DIR__ . '/build/cache/docs/%version%',
    'default_opened_level' => 2,
);

return new Sami($iterator, $options);
