<?php

/** @var app\models\SignupForm $signupForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<main class="container container--registration">
  <div class="center-block">
    <div class="registration-form regular-form">

      <?php $form = ActiveForm::begin([
        'options' => ['novalidate' => 'novalidate'],
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

      <h3 class="head-main head-task">Регистрация нового пользователя</h3>

      <?= $form->field($signupForm, 'username')->textInput([
        'id' => 'username',
        'class' => false
      ])
        ?>

      <div class="half-wrapper">

        <?= $form->field($signupForm, 'email')->input('email', [
          'id' => 'email-user',
          'class' => false
        ])
          ?>


        <?= $form->field($signupForm, 'city_id')->dropDownList(
          $cities,
          [
            'id' => 'town-user',
            'class' => false,
            'prompt' => 'Выберите город',
          ]
        ) ?>

      </div>
      <div class="half-wrapper">

        <?= $form->field($signupForm, 'password')->passwordInput([
          'id' => 'password-user',
          'class' => false
        ])
          ?>
      </div>

      <div class="half-wrapper">

        <?= $form->field($signupForm, 'password_repeat')->passwordInput([
          'id' => 'password-repeat-user',
          'class' => false
        ])
          ?>

      </div>

      <?= $form->field($signupForm, 'is_contractor', [
        'template' => "{input}\n{error}",
      ])->checkbox([
            'id' => 'response-user',
            'uncheck' => 0,
            'labelOptions' => ['class' => 'control-label checkbox-label'],
          ], true)
        ?>

      <?= Html::submitInput('Создать аккаунт', [
        'class' => 'button button--blue',
      ]) ?>

      <?php ActiveForm::end(); ?>
    </div>
  </div>
</main>