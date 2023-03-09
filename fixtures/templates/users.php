<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'full_name' => $faker->name,
    'email' => $faker->email,
    'password' => Yii::$app->getSecurity()->generatePasswordHash('password_' . $index),
    'auth_key' => Yii::$app->getSecurity()->generateRandomString(),
    'birthdate' => $faker->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d H:i:s'),
    'phone' => $faker->optional(0.9)->numerify('###########'),
    'telegram' => $faker->optional(0.9)->userName(),
    'about' => $faker->optional(0.9)->realText(250),
    'city_id' => $faker->numberBetween(1, 10),
    'avatar_id' => $faker->unique()->numberBetween(1, 10),
];