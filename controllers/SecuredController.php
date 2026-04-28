<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;

abstract class SecuredController extends Controller
{
  /**
   * {@inheritdoc}
   */
  public function behaviors()
  {
    return [
      'acсess' => [
        'class' => AccessControl::class,
        'rules' => [
          [
            'allow' => true,
            'roles' => ['@'], // залогиненные пользователи
          ]
        ],
        'denyCallback' => function ($rule, $action) {
          return $this->redirect(['landing/index']);
        }
      ]
    ];

  }
}