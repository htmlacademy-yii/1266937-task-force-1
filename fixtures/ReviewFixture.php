<?php

namespace app\fixtures;

use yii\test\ActiveFixture;
use app\fixtures\UserFixture;
use app\fixtures\TaskFixture;
use app\models\Review;

class ReviewFixture extends ActiveFixture
{
  public $modelClass = Review::class;

  public $depends = [
    UserFixture::class,
    TaskFixture::class,
  ];
}