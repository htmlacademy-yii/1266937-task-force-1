<?php

/** @var $faker \Faker\Generator */
/** @var $index integer */

return [
  'user_id' => $index + 1,
  'category_id' => $faker->numberBetween(1, 8),
];
