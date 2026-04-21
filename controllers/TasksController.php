<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use app\models\Task;
use app\models\TaskSearch;
use app\models\Category;

class TasksController extends Controller
{
  /**
   * Показывает список задач с фильтрацией
   * @return string
   */
  public function actionIndex(): string
  {
    $searchModel = new TaskSearch();
    $tasksDataProvider = $searchModel->search(Yii::$app->request->get());

    $categories = Category::find()->all();
    $categories = ArrayHelper::map($categories, 'id', 'name');

    return $this->render('index', [
      'tasksDataProvider' => $tasksDataProvider,
      'searchModel' => $searchModel,
      'categories' => $categories,
    ]);
  }

  /**
   * Показывает одно задание с откликами
   * @param int $id Идентификатор задания
   * @throws NotFoundHttpException
   * @return string
   */
  public function actionView(int $id): string
  {
    $task = Task::find()
      ->where(['id' => $id])
      ->with(['category', 'customer'])
      ->one();

    if (!$task) {
      throw new NotFoundHttpException("Задание с ID {$id} не найдено");
    }

    $ResponsesDataProvider = new ActiveDataProvider([
      'query' => $task->getResponses()->with(['contractor.customerReviews']),
      'pagination' => false,
      'sort' => [
        'defaultOrder' => ['created_at' => SORT_DESC],
      ]
    ]);

    return $this->render('view', [
      'task' => $task,
      'ResponsesDataProvider' => $ResponsesDataProvider,
    ]);
  }
}
