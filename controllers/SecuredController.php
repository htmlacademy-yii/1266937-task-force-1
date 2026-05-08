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
      'access' => [
        'class' => AccessControl::class,
        'rules' => [
          [
            'allow' => true,
            'roles' => ['@'],
          ]
        ],
        'denyCallback' => function ($rule, $action) {
          return $this->redirect(['landing/index']);
        }
      ]
    ];

  }
}