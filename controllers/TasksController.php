<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Task;

class TasksController extends Controller
{
  public function actionIndex()
  {
    $tasks = Task::find()
      ->where(['status' => 'new'])
      ->with(['category'])
      ->orderBy(['created_at' => SORT_DESC])
      ->all();

    return $this->render(
      'index',
      [
        'tasks' => $tasks
      ]
    );
  }
}
