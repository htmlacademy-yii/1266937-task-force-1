<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\models\Task;
use app\models\TaskSearch;
use app\models\Category;
use app\models\TaskForm;
class TasksController extends SecuredController
{
  /**
   * {@inheritdoc}
   */
  public function behaviors()
  {
    return ArrayHelper::merge(parent::behaviors(), [
      'access' => [
        'rules' => [
          [
            'actions' => ['create'],
            'allow' => true,
            'roles' => ['customer'],
          ],
        ],
      ],
    ]);
  }
  /**
   * Показывает список задач с фильтрацией
   * 
   * @return string
   */
  public function actionIndex(): string
  {
    $searchModel = new TaskSearch();
    $tasksDataProvider = $searchModel->search(Yii::$app->request->get());

    $categories = Category::find()->select(['name', 'id'])->indexBy('id')->column();

    return $this->render('index', [
      'tasksDataProvider' => $tasksDataProvider,
      'searchModel' => $searchModel,
      'categories' => $categories,
    ]);
  }

  /**
   * Показывает одно задание с откликами
   * 
   * @param int $id ID задания
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

    $responsesDataProvider = new ActiveDataProvider([
      'query' => $task->getResponses()->with(['contractor.receivedReviews']),
      'pagination' => false,
      'sort' => [
        'defaultOrder' => ['created_at' => SORT_DESC],
      ]
    ]);

    return $this->render('view', [
      'task' => $task,
      'responsesDataProvider' => $responsesDataProvider,
    ]);
  }

  /**
   * Создает новое задание
   * 
   * @return string|Response
   */
  public function actionCreate(): string|Response
  {
    $taskForm = new TaskForm();
    $categories = Category::find()->select(['name', 'id'])->indexBy('id')->column();

    if ($taskForm->load(Yii::$app->request->post())) {
      if ($task = $taskForm->createTask()) {
        return $this->redirect(['view', 'id' => $task->id]);
      }
    }

    return $this->render('create', [
      'taskForm' => $taskForm,
      'categories' => $categories,
    ]);
  }
}