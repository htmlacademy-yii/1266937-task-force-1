<?php

/** @var $faker \Faker\Generator */

$path = 'img/avatars/' . $faker->numberBetween(1, 5) . '.png';

return [
  'file_path' => $path,
  'url' => $path,
];