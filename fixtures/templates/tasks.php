<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
use Taskforce\Main\TaskStatuses;

$statuses = array_keys(TaskStatuses::getStatusesMap());

return [
    'title' => $faker->realText(30),
    'description' => $faker->realText(500, 2),
    'price' => $faker->numberBetween(100, 10000),
    'published_at' => $faker->dateTimeBetween('-2 day')->format('Y-m-d H:i:s'),
    'expired_at' => $faker->dateTimeBetween('now', '+1 month')->format('Y-m-d H:i:s'),
    'current_status' => $faker->randomElement($statuses),
    'category_id' => $faker->numberBetween(1, 8),
    'client_id' => $faker->numberBetween(1, 5),
    'worker_id' => $faker->optional()->randomDigit(),
    'city_id' => $faker->numberBetween(1, 10)
];
