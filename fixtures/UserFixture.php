<?php

namespace app\fixtures;

use yii\test\ActiveFixture;
use app\models\User;

class UserFixture extends ActiveFixture
{
  public $modelClass = User::class;

  public $depends = [
    '\app\fixtures\FileFixture',
  ];

}
