<?php

/** @var $faker \Faker\Generator */

use app\models\User;
use app\models\Task;
use app\models\Response;

return [
  'created_at' => $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d H:i:s'),
  'task_id' => $faker->randomElement(Task::find()->select('id')->column()),
  'contractor_id' => $faker->randomElement(User::find()->select('id')->where(['role' => 'contractor'])->column()),
  'text_comment' => $faker->optional(0.8)->text(150),
  'price' => $faker->optional(0.8)->numberBetween(500, 20000),
  'STATUS' => $faker->randomElement(array_keys(Response::optsSTATUS())),
];
