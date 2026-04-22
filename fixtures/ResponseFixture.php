<?php

namespace app\fixtures;

use yii\test\ActiveFixture;
use app\fixtures\UserFixture;
use app\fixtures\TaskFixture;
use app\models\Response;

class ResponseFixture extends ActiveFixture
{
  public $modelClass = Response::class;

  public $depends = [
    UserFixture::class,
    TaskFixture::class,
  ];
}