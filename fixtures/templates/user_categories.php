<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use app\models\Categories;
$categories = Categories::find()->all();

return [
    'category_id' => $faker->numberBetween(1, 8),
    'user_id' => $faker->numberBetween(1, 5),
];
