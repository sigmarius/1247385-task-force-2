<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Taskforce\Service\Helpers\GenerateDataFromCsv;

$file = [
    'path' => '/src/data/cities.csv',
    'columns' => ['name', 'latitude', 'longitude']
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
        'latitude' => $data['latitude'][$randomKey],
        'longitude' => $data['longitude'][$randomKey],
    ];
}
