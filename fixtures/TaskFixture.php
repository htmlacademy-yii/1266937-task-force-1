<?php

namespace app\fixtures;

use yii\test\ActiveFixture;
use app\fixtures\UserFixture;
use app\models\Task;

class TaskFixture extends ActiveFixture
{
  public $modelClass = Task::class;

  public $depends = [
    UserFixture::class,
  ];
}