<?php

use Yii;
use yii\web\Controller;
use app\models\SignUpForm;
use app\models\User;

class SignUpController extends Controller
{

  /**
   * Показывает страницу регистрации пользователя
   * 
   * @return string
   */
  public function actionIndex(): string
  {
    $signUpForm = new SignUpForm();

    // Проверить, что отправлена форма
    if ($signUpForm->load(Yii::$app->request->post())) {
      if ($signUpForm->validate()) {
        // Если ошибок нет, то сохранить данные формы в таблице пользователей
        $user = new User();

        $user->username = $signUpForm->username;
        $user->email = $signUpForm->email;
        $user->city_id = $signUpForm->city_id;
        $user->password_hash = Yii::$app->security->generatePasswordHash($signUpForm->password);
        $user->role = $signUpForm->is_contractor ? User::ROLE_CONTRACTOR : User::ROLE_CUSTOMER;

        // false нужен, чтобы повторно не выполнялась валидация
        if ($user->save(false)) {
          $this->redirect(['tasks/index']);
        }
      }
    }

    return $this->render('index', [
      'signUpForm' => $signUpForm,
    ]);
  }
}