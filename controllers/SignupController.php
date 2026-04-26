<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\SignupForm;
use app\models\City;

class SignupController extends Controller
{
  /**
   * Показывает страницу регистрации пользователя
   * 
   * @return Response|string
   */
  public function actionIndex(): Response|string
  {
    $signupForm = new SignupForm();
    $cities = City::find()->select(['name', 'id'])->indexBy('id')->column();

    if ($signupForm->load(Yii::$app->request->post())) {
      if ($user = $signupForm->signup()) {
        Yii::$app->user->login($user);
        return $this->redirect(['tasks/index']);
      }
    }

    return $this->render('index', [
      'signupForm' => $signupForm,
      'cities' => $cities,
    ]);
  }
}