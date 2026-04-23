<?php

namespace app\fixtures;

use yii\test\ActiveFixture;
use app\models\File;

class FileFixture extends ActiveFixture
{
  public $modelClass = File::class;
}