<?php

namespace app\controllers;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use app\models\AccountSettingsForm;
use app\models\SecuritySettingsForm;
use app\models\User;
use app\models\Category;

class SettingsController extends SecuredController
{
  /**
   *Показывает форму настроек профиля
   * 
   * @return string|Yii\web\Response Возвращает отрендеренную страницу настроек 
   *                                  или объект Response при успешном редиректе
   */
  public function actionIndex()
  {
    $user = User::findOne(Yii::$app->user->id);
    $categories = ArrayHelper::map(Category::find()->all(), 'id', 'name');

    $accountSettingsForm = new AccountSettingsForm();
    $accountSettingsForm->loadData($user);

    if ($accountSettingsForm->load(Yii::$app->request->post())) {
      $accountSettingsForm->avatarFile = UploadedFile::getInstance($accountSettingsForm, 'avatarFile');

      if ($accountSettingsForm->save()) {

        return $this->redirect(['users/view', 'id' => $user->id]);
      }
    }

    return $this->render('index', [
      'accountSettingsForm' => $accountSettingsForm,
      'user' => $user,
      'categories' => $categories,
    ]);
  }

  /**
   * Показывает форму настроек безопасности и обрабатывает изменение пароля/приватности
   * 
   * @return string|Yii\web\Response Возвращает отрендеренную страницу безопасности или объект Response при успешном редиректе
   */
  public function actionSecurity()
  {
    $user = User::findOne(Yii::$app->user->id);
    $securitySettingsForm = new SecuritySettingsForm();
    $securitySettingsForm->loadData($user);

    $isContractor = Yii::$app->user->can('contractor');

    if ($securitySettingsForm->load(Yii::$app->request->post()) && $securitySettingsForm->save()) {
      return $this->redirect(['users/view', 'id' => $user->id]);
    }

    return $this->render('security', [
      'securitySettingsForm' => $securitySettingsForm,
      'isContractor' => $isContractor,
    ]);
  }
}