<?php

namespace app\controllers;

use Yii;
use app\models\Task;
use app\models\TaskSearch;

class MyTasksController extends SecuredController
{
  /**
   * Показывает страницу Мои задания
   * @param string $status Текущий фильтр по статусу
   * 
   * @return string
   */
  public function actionIndex(string $status = 'new'): string
  {
    $userId = Yii::$app->user->id;
    $isCustomer = Yii::$app->user->can('customer');
    $now = date('Y-m-d H:i:s');

    if (!$isCustomer && $status === 'new') {
      $status = 'in_progress';
    }

    $menuItems = $isCustomer ? [
      'new' => 'Новые',
      'in_progress' => 'В процессе',
      'closed' => 'Закрытые'
    ] : [
      'in_progress' => 'В процессе',
      'expired' => 'Просрочено',
      'closed' => 'Закрытые'
    ];

    $query = Task::find()->andWhere([($isCustomer ? 'customer_id' : 'contractor_id') => $userId]);

    $isTasksNew = ['STATUS' => Task::STATUS_NEW];
    $isTasksInProgress = ['STATUS' => Task::STATUS_IN_PROGRESS];
    $isTasksExpired = ['and', $isTasksInProgress, ['<', 'deadline_at', $now]];
    $isTasksCurrent = ['and', $isTasksInProgress, ['or', ['>=', 'deadline_at', $now], ['is', 'deadline_at', null]]];
    $customerCompletedTasks = ['in', 'STATUS', [Task::STATUS_CANCELED, Task::STATUS_COMPLETED, Task::STATUS_FAILED]];
    $executorCompletedTasks = ['in', 'STATUS', [Task::STATUS_COMPLETED, Task::STATUS_FAILED]];

    $filter = match ($status) {
      'new' => $isTasksNew,
      'in_progress' => $isCustomer ? $isTasksInProgress : $isTasksCurrent,
      'expired' => $isTasksExpired,
      'closed' => $isCustomer ? $customerCompletedTasks : $executorCompletedTasks,
      default => $isCustomer ? $isTasksNew : $isTasksInProgress
    };

    $query->andWhere($filter);

    $searchModel = new TaskSearch();

    $tasksDataProvider = $searchModel->search(Yii::$app->request->get(), $query);

    return $this->render('index', [
      'tasksDataProvider' => $tasksDataProvider,
      'currentStatus' => $status,
      'isCustomer' => $isCustomer,
      'menuItems' => $menuItems,
    ]);
  }
}