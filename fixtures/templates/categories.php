<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Taskforce\Service\Helpers\GenerateDataFromCsv;

$file = [
    'path' => '/src/data/categories.csv',
    'columns' => ['name', 'icon'],
];

$data = new GenerateDataFromCsv($file);
$data = $data->makeArrays();

try {
    $randomKey = $faker->unique()->randomKey($data['keys']);
} catch (\OverflowException $e) {
    echo "Превышено максимальное количество записей для данного набора данных - " . count($data['keys']) . PHP_EOL;
}

if (!empty($randomKey)) {
    return [
        'name' => $data['name'][$randomKey],
        'icon' => $data['icon'][$randomKey],
    ];
}

