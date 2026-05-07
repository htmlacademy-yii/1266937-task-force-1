<?php

namespace app\controllers;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use app\models\AccountSettingsForm;
use app\models\User;
use app\models\Category;

class SettingsController extends SecuredController
{
  /**
   * Summary of actionIndex
   * 
   * @return string|Yii\web\Response
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
}