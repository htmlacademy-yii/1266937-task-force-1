<?php

/** @var $faker \Faker\Generator */
/** @var integer $index */

use app\models\User;
use app\models\City;

$cityIds = City::find()->select('id')->column();
$phone = $faker->optional(0.7)->e164PhoneNumber();
$telegram = $faker->optional(0.7)->userName();

return [
  'created_at' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s'),
  'email' => $faker->unique()->safeEmail(),
  'username' => $faker->firstName(),
  'password_hash' => Yii::$app->getSecurity()->generatePasswordHash($faker->password()),
  'role' => $faker->randomElement(array_keys(User::optsRole())),
  'avatar_id' => $faker->optional(0.8)->numberBetween(1, 5),
  'city_id' => $faker->randomElement($cityIds),
  'birthday' => $faker->optional(0.7)->date('Y-m-d', '-18 years'),
  'phone' => $phone ? substr($phone, -11) : null,
  'telegram' => $telegram ? "@{$telegram}" : null,
  'profile_info' => $faker->optional(0.7)->text(200),
];