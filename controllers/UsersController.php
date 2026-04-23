<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use app\models\User;

class UsersController extends Controller
{

  public function actionView(int $id): string
  {
    $user = User::find()
      ->where(['id' => $id])
      ->with(['categories', 'city', 'avatar', 'contractorTasks'])
      ->one();

    if (!$user) {
      throw new NotFoundHttpException("Пользователь с ID {$id} не найден");
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