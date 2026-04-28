<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

\yii\widgets\ActiveFormAsset::register($this);

?>

<section class="modal enter-form form-modal" id="enter-form">
  <h2>Вход на сайт</h2>
  <?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'enableAjaxValidation' => true,
    'validateOnSubmit' => true,
    'fieldConfig' => [
      'options' => ['tag' => 'p'],
      'template' => "{label}\n{input}\n{error}",
      'labelOptions' => ['class' => 'form-modal-description'],
      'inputOptions' => ['class' => 'enter-form-email input input-middle'],
      'errorOptions' => [
        'tag' => 'span',
        'class' => 'help-block',
      ],
    ],
  ]); ?>

  <?= $form->field($loginForm, 'email')->textInput() ?>
  <?= $form->field($loginForm, 'password')->passwordInput() ?>

  <?= Html::submitButton('Войти', [
    'class' => 'button',
  ]) ?>

  <?php ActiveForm::end(); ?>

  <button class="form-modal-close" type="button">Закрыть</button>
</section>