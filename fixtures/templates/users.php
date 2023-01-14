<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'full_name' => $faker->name,
    'email' => $faker->email,
    'password' => Yii::$app->getSecurity()->generatePasswordHash('password_' . $index),
    'city_id' => $faker->numberBetween(0, 500)
];