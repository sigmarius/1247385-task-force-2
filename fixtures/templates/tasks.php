<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'title' => $faker->realText(30),
    'description' => $faker->realText(500, 2),
    'price' => $faker->numberBetween(100, 10000),
    'published_at' => $faker->dateTimeBetween('-2 day')->format('Y-m-d H:i:s'),
    'expired_at' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s'),
    'current_status' => \Taskforce\Service\Task\TaskStatuses::STATUS_NEW,
    'category_id' => $faker->numberBetween(1, 8),
    'client_id' => 2,
    'city_id' => $faker->numberBetween(1, 4)
];
