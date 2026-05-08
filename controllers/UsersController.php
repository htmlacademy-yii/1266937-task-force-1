<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use app\models\User;

class UsersController extends SecuredController
{

  public function actionView($id): string
  {
    $user = User::find()
      ->where(['id' => $id])
      ->with(['categories', 'city', 'avatar', 'contractorTasks'])
      ->one();

    if (!$user) {
      throw new NotFoundHttpException("Пользователь не найден");
    }

    if (!Yii::$app->authManager->getAssignment('contractor', $user->id)) {
      throw new NotFoundHttpException("Страница не найдена");
    }

    $reviewsDataProvider = new ActiveDataProvider([
      'query' => $user->getReceivedReviews()->with(['customer.avatar']),
      'pagination' => false,
      'sort' => [
        'defaultOrder' => ['created_at' => SORT_DESC],
      ],
    ]);

    return $this->render('view', [
      'user' => $user,
      'reviewsDataProvider' => $reviewsDataProvider,
    ]);
  }
}