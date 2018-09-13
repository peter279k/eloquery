<?php

$dir = __DIR__ . '/../src';

$iterator = Symfony\Component\Finder\Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('tests')
    ->in($dir);

$versions = Sami\Version\GitVersionCollection::create($dir)
    ->add('v0.1.0', 'Master');

$options = [
    'theme' => 'default',
    'title' => 'EloQuery API Documentation',
    'versions' => $versions,
    'build_dir' => __DIR__ . '/api/%version%',
    'cache_dir' => __DIR__ . '/cache/%version%',
];

$sami = new Sami\Sami($iterator, $options);

return $sami;