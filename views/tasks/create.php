<?php

/** @var app\models\TaskForm $taskForm */
/** @var array $categories */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

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
      <label class="control-label" for="location">Локация</label>
      <input class="location-icon" id="location" type="text">
      <span class="help-block">Error description is here</span>
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
      Добавить новый файл
    </div>
    <?= Html::submitInput('Опубликовать', ['class' => 'button button--blue']) ?>
    <?php ActiveForm::end(); ?>
  </div>
</main>