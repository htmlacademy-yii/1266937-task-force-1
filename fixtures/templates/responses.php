<?php

/** @var $faker \Faker\Generator */

use app\models\User;
use app\models\Task;
use app\models\Response;

$taskIds = Task::find()->select('id')->column();
$contractorIds = User::find()->select('id')->where(['role' => 'contractor'])->column();

return [
  'created_at' => $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d H:i:s'),
  'task_id' => $faker->randomElement($taskIds),
  'contractor_id' => $faker->randomElement($contractorIds),
  'text_comment' => $faker->optional(0.8)->realText(150),
  'price' => $faker->optional(0.8)->numberBetween(500, 9999),
  'STATUS' => $faker->randomElement(array_keys(Response::optsSTATUS())),
];
