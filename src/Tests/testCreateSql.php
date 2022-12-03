<?php
require_once 'vendor/autoload.php';

use Taskforce\Service;

$files = [
    [
        'path' => '/data/categories.csv',
        'columns' => ['name', 'icon'],
    ],
    [
        'path' => '/data/cities.csv',
        'columns' => ['name', 'latitude', 'longitude']
    ]
];

foreach ($files as $file) {
    $loader = new Service\SqlFromCsvCreater($file['path'], $file['columns']);
    $loader->createSqlFile();
}

