<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\models\Task;
use app\models\TaskSearch;
use app\models\Category;
use app\models\TaskForm;
use app\models\Review;

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

    $failedModel = Yii::$app->session->getFlash('failedModel');

    $responseModel = ($failedModel instanceof \app\models\Response)
      ? $failedModel
      : new \app\models\Response();

    $reviewModel = ($failedModel instanceof Review)
      ? $failedModel
      : new Review();

    return $this->render('view', [
      'task' => $task,
      'responsesDataProvider' => $responsesDataProvider,
      'responseModel' => $responseModel,
      'reviewModel' => $reviewModel,
    ]);
  }

  /**
   * Создает новое задание
   * 
   * @return Response|string
   */
  public function actionCreate(): Response|string
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

  public function actionHandle(int $id, string $actionCodeName, ?int $responseId = null)
  {
    $task = Task::findOne($id);

    if (!$task) {
      throw new NotFoundHttpException("Задание с id {id} не найдено");
    }

    $userId = Yii::$app->user->id;
    $currentAction = null;

    foreach ($task->getActionsByStatus($task->STATUS) as $action) {
      if ($action->getCodeName() === $actionCodeName) {
        $currentAction = $action;

        break;
      }
    }

    if ($currentAction?->isAllowed($userId, $task->customer_id, $task->contractor_id, $task->STATUS)) {

      $params = Yii::$app->request->post();

      if ($responseId) {
        $params['responseId'] = $responseId;
      }

      if ($currentAction->execute($task, $params)) {
        Yii::$app->session->setFlash('success', "Действие {$currentAction->getName()} выполнено");
      } else {
        Yii::$app->session->setFlash('error', "Не удалось выполнить действие");

        if (method_exists($currentAction, 'getModel')) {
          Yii::$app->session->setFlash('failedModel', $currentAction->getModel());
        }
      }
    }
    return $this->redirect(['view', 'id' => $task->id]);
  }
}