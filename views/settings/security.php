<?php

/** @var yii\web\View $this */
/** @var app\models\SecuritySettingsForm $securitySettingsForm */
/** @var app\models\User $user */
/** @var bool $isContractor */

use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = 'Безопасность';

?>

<main class="main-content main-content--left container">

  <?= $this->render('_menu') ?>

  <div class="my-profile-form">
    <?php $form = ActiveForm::begin([
      'enableClientValidation' => false,
      'fieldConfig' => [
        'options' => ['class' => 'form-group'],
        'template' => "{label}\n{input}\n{error}",
        'labelOptions' => ['class' => 'control-label'],
        'errorOptions' => [
          'tag' => 'span',
          'class' => 'help-block'
        ],
      ],
    ]); ?>

    <h3 class="head-main head-regular">Безопасность</h3>

    <?= $form->field($securitySettingsForm, 'oldPassword')->passwordInput([
      'id' => 'profile-old-password',
      'class' => false
    ]) ?>

    <?= $form->field($securitySettingsForm, 'newPassword')->passwordInput([
      'id' => 'profile-new-password',
      'class' => false
    ]) ?>

    <?= $form->field($securitySettingsForm, 'newPasswordRepeat')->passwordInput([
      'id' => 'profile-password-repeat',
      'class' => false
    ]) ?>

    <?php if ($isContractor): ?>
      <?= $form->field($securitySettingsForm, 'is_contacts_public', [
        'template' => "{input}\n{error}",
      ])->checkbox([
            'id' => 'show-contacts',
            'uncheck' => 0,
            'labelOptions' => ['class' => 'control-label checkbox-label'],
          ], true)
        ?>
      <?php endif; ?>

    <?= Html::submitInput('Сохранить', [
      'class' => 'button button--blue',
    ]) ?>

    <?php ActiveForm::end(); ?>
  </div>
</main>