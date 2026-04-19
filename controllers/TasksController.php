<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use app\models\TaskSearch;
use app\models\Category;

class TasksController extends Controller
{
  public function actionIndex()
  {
    $searchModel = new TaskSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->get());

    $categories = Category::find()->all();
    $categories = ArrayHelper::map($categories, 'id', 'name');

    return $this->render('index', [
      'dataProvider' => $dataProvider,
      'searchModel' => $searchModel,
      'categories' => $categories,
    ]);

  }
}
