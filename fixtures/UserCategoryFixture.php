<?php

namespace app\fixtures;

use yii\test\ActiveFixture;
use app\models\UserCategory;

class UserCategoryFixture extends ActiveFixture
{
  public $modelClass = UserCategory::class;

  public $dataFile = __DIR__ . '/data/userCategories.php';

  public $depends = ['\app\fixtures\UserFixture'];
}