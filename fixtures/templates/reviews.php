<?php

/** @var $faker \Faker\Generator */

use app\models\User;
use app\models\Task;

$taskIds = Task::find()->select('id')->column();
$customerIds = User::find()->select('id')->where(['role' => 'customer'])->column();
$contractorIds = User::find()->select('id')->where(['role' => 'contractor'])->column();

return [
  'created_at' => $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d H:i:s'),
  'task_id' => $faker->randomElement(Task::find()->select('id')->column()),
  'customer_id' => $faker->randomElement($customerIds),
  'contractor_id' => $faker->randomElement($contractorIds),
  'text_comment' => $faker->realText(150),
  'rating' => $faker->numberBetween(1, 5),
];