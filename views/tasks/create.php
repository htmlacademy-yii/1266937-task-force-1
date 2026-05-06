<?php

/** @var app\models\TaskForm $taskForm */
/** @var array $categories */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\assets\VendorAsset;

VendorAsset::register($this);
$this->registerJsFile('@web/js/init-autocomplete.js', ['depends' => [VendorAsset::class]]);

$userCity = Yii::$app->user->identity->city->name ?? '';
$this->registerJsVar('userCity', $userCity);

?>

<style>
  .autoComplete_wrapper {
    display: block !important;
    width: 100% !important;
  }

  .autoComplete_wrapper>input {
    width: 100% !important;
    height: 39px !important;
    padding: 9px !important;
    background-image: url('/img/location-icon.svg') !important;
    background-repeat: no-repeat !important;
    background-repeat: no-repeat !important;
    background-size: 19px 24px !important;
    background-position: 630px 6px !important;
  }

  .autoComplete_wrapper>input {
    background-image: none;
  }
</style>

<main class="main-content main-content--center container">
  <div class="add-task-form regular-form">

    <?php $form = ActiveForm::begin([
      'id' => 'task-form',
      'enableClientValidation' => false,
      'options' => ['enctype' => 'multipart/form-data'],
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

    <h3 class="head-main head-main">Публикация нового задания</h3>

    <?= $form->field($taskForm, 'title')->textInput([
      'id' => 'essence-work',
    ]) ?>

    <?= $form->field($taskForm, 'description')->textarea([
      'id' => 'username',
    ]) ?>

    <?= $form->field($taskForm, 'category_id')->dropDownList(
      $categories,
      ['id' => 'town-user', 'prompt' => 'Выберите категорию']
    ) ?>

    <div class="form-group">
      <?= $form->field($taskForm, 'location', [
        'template' => "{label}\n{input}\n{error}"
      ])->textInput([
            'id' => 'location',
            'class' => 'location-icon',
            'autocomplete' => 'off'
          ]) ?>

      <?= Html::activeHiddenInput($taskForm, 'latitude', ['id' => 'latitude']) ?>
      <?= Html::activeHiddenInput($taskForm, 'longitude', ['id' => 'longitude']) ?>
      <?= Html::activeHiddenInput($taskForm, 'city_id', ['id' => 'city_id']) ?>
    </div>

    <div class="half-wrapper">

      <?= $form->field($taskForm, 'budget')->textInput([
        'id' => 'budget',
        'class' => 'budget-icon'
      ]) ?>

      <?= $form->field($taskForm, 'deadline_at')->input('date', [
        'id' => 'period-execution'
      ]) ?>
    </div>

    <p class="form-label">Файлы</p>
    <div class="new-file">
      <?= $form->field($taskForm, 'uploadedFiles[]', [
        'template' => '{input}',
        'options' => ['tag' => false]
      ])->fileInput([
            'id' => 'file-upload',
            'multiple' => true,
            'style' => 'display:none',
          ]) ?>
      <label for="file-upload" style="cursor:pointer">
        Добавить новый файл
      </label>
    </div>

    <?= Html::submitInput('Опубликовать', ['class' => 'button button--blue']) ?>

    <?php ActiveForm::end(); ?>
  </div>
</main>