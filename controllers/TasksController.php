<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
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
    $behaviors = parent::behaviors();

    $behaviors['access']['rules'] = [
      [
        'actions' => ['create'],
        'allow' => true,
        'roles' => ['customer'],
      ],
      [
        'actions' => ['create'],
        'allow' => false,
      ],
      [
        'allow' => true,
        'roles' => ['@'],
      ],
    ];

    return $behaviors;
  }

  /**
   * Показывает список заданий с фильтрацией
   * 
   * @return string
   */
  public function actionIndex(): string
  {
    $searchModel = new TaskSearch();
    $categories = Category::find()->select(['name', 'id'])->indexBy('id')->column();

    $query = Task::find()
      ->where(['tasks.STATUS' => Task::STATUS_NEW])
      ->with('category');

    $searchModel->load(Yii::$app->request->get(), '');

    if ($searchModel->validate()) {
      $query->andFilterWhere(['category_id' => $searchModel->category_id]);

      if ($searchModel->isRemote) {
        $query->andWhere(['tasks.location' => null]);
      }

      if ($searchModel->noResponses) {
        $query->joinWith('responses')->andWhere(['responses.id' => null]);
      }

      if ($searchModel->interval) {
        $date = (new \DateTime())->modify("- $searchModel->interval")->format('Y-m-d H:i:s');
        $query->andWhere(['>=', 'tasks.created_at', $date]);
      }
    }

    $tasksDataProvider = $searchModel->search(Yii::$app->request->get(), $query);

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

    if ($failedModel instanceof Review) {
      $reviewModel->validate();
    }

    if ($failedModel instanceof \app\models\Response) {
      $responseModel->validate();
    }

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
      $taskForm->uploadedFiles = UploadedFile::getInstances($taskForm, 'uploadedFiles');

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
      throw new NotFoundHttpException("Задание с id {$id} не найдено");
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