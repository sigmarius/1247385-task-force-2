<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'worker_id' => 3,
    'task_id' => $faker->numberBetween(1, 50),
    'worker_price' => $faker->numberBetween(1000, 50000),
    'comment' => $faker->realText(150),
    'date_created' => $faker->dateTimeBetween('-10 day')->format('Y-m-d H:i:s'),
];
