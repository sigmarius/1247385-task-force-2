<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
use Taskforce\Logic\Task;

$task = new Task(1);
$statuses = array_keys($task->getStatusesMap());

return [
    'title' => $faker->sentence(),
    'description' => $faker->realText(250, 2),
    'price' => $faker->numberBetween(100, 100000),
    'published_at' => $faker->dateTimeBetween('-2 day')->format('Y-m-d H:i:s'),
    'expired_at' => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
    'current_status' => $faker->randomElement($statuses),
    'category_id' => $faker->numberBetween(1, 8),
    'client_id' => $faker->numberBetween(1, 5),
    'worker_id' => $faker->optional()->randomDigit(),
    'city_id' => $faker->numberBetween(1, 500)
];
