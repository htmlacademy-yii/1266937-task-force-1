<?php

/** @var yii\web\View $this */
/** @var app\models\AccountSettingsForm $accountSettingsForm */
/** @var array $categories */
/** @var app\models\User $user */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = 'Мой профиль';

?>

<main class="main-content main-content--left container">
  <div class="left-menu left-menu--edit">
    <h3 class="head-main head-task">Настройки</h3>
    <ul class="side-menu-list">
      <li class="side-menu-item side-menu-item--active">
        <a class="link link--nav">Мой профиль</a>
      </li>
      <li class="side-menu-item">
        <a href="<?= Url::to(['settings/security']) ?>" class="link link--nav">Безопасность</a>
      </li>
    </ul>
  </div>
  <div class="my-profile-form">

    <?php $form = ActiveForm::begin([
      'id' => 'account-settings-form',
      'enableClientValidation' => false,
      'options' => [
        'enctype' => 'multipart/form-data',
        'novalidate' => true,
      ],
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

    <h3 class="head-main head-regular">Мой профиль</h3>
    <div class="photo-editing">
      <div>

        <p class="form-label">Аватар</p>
        <img class="avatar-preview" src="<?= Url::to($user->avatar
          ? $user->avatar->url
          : '/img/avatars/4.png', true) ?>" width="83" height="83">

      </div>

      <?= $form->field($accountSettingsForm, 'avatarFile', [
        'template' => "{input}\n{label}\n{error}",
        'options' => ['tag' => false],
        'labelOptions' => ['class' => 'button button--black']
      ])->fileInput([
            'id' => 'button-input',
            'style' => 'display: none;'
          ])->label('Сменить аватар') ?>

    </div>

    <?= $form->field($accountSettingsForm, 'username')->textInput([
      'id' => 'profile-name',
      'class' => false
    ])
      ?>

    <div class="half-wrapper">

      <?= $form->field($accountSettingsForm, 'email')->input('email', [
        'id' => 'profile-email',
        'class' => false
      ])
        ?>

      <?= $form->field($accountSettingsForm, 'birthday')->input('date', [
        'id' => 'profile-date',
        'class' => false,
      ]) ?>

    </div>
    <div class="half-wrapper">

      <?= $form->field($accountSettingsForm, 'phone')->textInput([
        'id' => 'profile-phone',
        'type' => 'tel',
        'class' => false
      ]) ?>

      <?= $form->field($accountSettingsForm, 'telegram')->textInput([
        'id' => 'profile-tg',
        'class' => false
      ]) ?>

    </div>

    <?= $form->field($accountSettingsForm, 'profile_info')->textarea([
      'id' => 'profile-info',
      'class' => false
    ]) ?>

    <div class="form-group">
      <p class="form-label">Выбор специализаций</p>

      <?= $form->field($accountSettingsForm, 'categoryIds', [
        'options' => ['tag' => false],
        'template' => "{input}\n{error}",
      ])->checkboxList($categories, [
            'tag' => 'div',
            'class' => 'checkbox-profile',
            'itemOptions' => [
              'labelOptions' => ['class' => 'control-label'],
            ],
          ]) ?>

    </div>

    <?= Html::submitInput('Сохранить', ['class' => 'button button--blue']) ?>.

    <?php ActiveForm::end(); ?>

  </div>
</main>