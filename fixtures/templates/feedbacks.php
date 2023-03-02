<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'client_id' => $faker->numberBetween(6, 10),
    'worker_id' => $faker->numberBetween(1, 5),
    'task_id' => $faker->unique()->numberBetween(1, 50),
    'comment' => $faker->realText(150),
    'rating' => $faker->numberBetween(1, 5),
    'date_created' => $faker->dateTimeBetween('-10 day')->format('Y-m-d H:i:s'),
];
