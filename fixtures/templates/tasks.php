<?php

/** var $faker \Faker\Generator */

use app\models\Task;
use app\models\Category;
use app\models\City;
use app\models\User;

$cities = City::find()->asArray()->all();
$city = $faker->optional(0.7)->randomElement($cities);
$categoryIds = Category::find()->select('id')->column();
$customerIds = User::find()->where(['role' => 'customer'])->select('id')->column();


return [
  'title' => $faker->sentence(5),
  'description' => $faker->text(150),
  'budget' => $faker->optional(0.8)->numberBetween(500, 20000),
  'deadline_at' => $faker->optional(0.7)->dateTimeBetween('now', '+2 month')?->format('Y-m-d H:i:s'),
  'created_at' => $faker->dateTimeBetween('-2 month', 'now')->format('Y-m-d H:i:s'),
  'customer_id' => $faker->randomElement($customerIds),
  'category_id' => $faker->randomElement($categoryIds),
  'city_id' => $city['id'] ?? null,
  'location' => $city['name'] ?? null,
  'latitude' => $city['lat'] ?? null,
  'longitude' => $city['long'] ?? null,
  'status' => $faker->randomElement(array_keys(Task::optsStatus())),
];
