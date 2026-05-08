<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\LoginForm;

class LandingController extends Controller
{
  public $layout = 'landing';

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
            'roles' => ['?'],
          ],
          [
            'actions' => ['logout'],
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
        'denyCallback' => function () {
          return $this->redirect(['tasks/index']);
        }
      ]
    ];
  }

  public function actionIndex()
  {
    $loginForm = new LoginForm();

    if ($loginForm->load(Yii::$app->request->post())) {
      if (Yii::$app->request->isAjax) {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return ActiveForm::validate($loginForm);
      }

      if ($loginForm->login()) {
        return $this->redirect(['tasks/index']);
      }


    }
    Yii::$app->view->params['loginForm'] = $loginForm;

    return $this->render('index', [
      'loginForm' => $loginForm,
    ]);
  }

  public function actionLogout()
  {
    Yii::$app->user->logout();

    return $this->goHome();
  }
}