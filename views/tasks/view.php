<?php

/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\Task $task */
/** @var app\models\Response $responseModel */
/** @var app\models\Review $reviewModel */

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use app\components\ButtonActionWidget;
use \app\services\actions\AbstractAction;

?>

<main class="main-content container">
  <div class="left-column">
    <div class="head-wrapper">
      <h3 class="head-main"><?= Html::encode($task->title) ?></h3>
      <p class="price price--big"><?= ($task->budget) ? Html::encode($task->budget) . '&nbsp;₽' : '' ?>
      </p>
    </div>
    <p class="task-description"><?= Html::encode($task->description) ?></p>

    <?= ButtonActionWidget::widget([
      'task' => $task,
      'type' => AbstractAction::TYPE_TASK
    ]) ?>

    <div class="task-map">
      <img class="map" src="/img/map.png" width="725" height="346" alt="Новый арбат, 23, к. 1">
      <p class="map-address town">Москва</p>
      <p class="map-address">Новый арбат, 23, к. 1</p>
    </div>

    <h4 class="head-regular">Отклики на задание</h4>

    <?= ListView::widget(
      [
        'dataProvider' => $responsesDataProvider,
        'itemView' => '_response',
        'viewParams' => [
          'task' => $task
        ],
        'layout' => '{items}',
        'options' => ['tag' => false],
        'itemOptions' => ['tag' => false],
      ]
    )
      ?>

  </div>

  <div class="right-column">
    <div class="right-card black info-card">
      <h4 class="head-card">Информация о задании</h4>
      <dl class="black-list">
        <dt>Категория</dt>
        <dd><?= Html::encode($task->category->name) ?></dd>
        <dt>Дата публикации</dt>
        <dd><?= Yii::$app->formatter->asRelativeTime($task->created_at) ?></dd>
        <dt>Срок выполнения</dt>
        <dd>
          <?= $task->deadline_at ? Yii::$app->formatter->asDatetime($task->deadline_at, 'php:j F') : 'Не указан' ?>
        </dd>
        <dt>Статус</dt>
        <dd><?= $task->displaySTATUS() ?></dd>
      </dl>
    </div>

    <div class="right-card white file-card">
      <h4 class="head-card">Файлы задания</h4>
      <ul class="enumeration-list">
        <li class="enumeration-item">
          <a href="#" class="link link--block link--clip">my_picture.jpg</a>
          <p class="file-size">356 Кб</p>
        </li>
        <li class="enumeration-item">
          <a href="#" class="link link--block link--clip">information.docx</a>
          <p class="file-size">12 Кб</p>
        </li>
      </ul>
    </div>
  </div>
</main>

<section class="pop-up pop-up--refusal pop-up--close">
  <div class="pop-up--wrapper">
    <h4>Отказ от задания</h4>
    <p class="pop-up-text">
      <b>Внимание!</b><br>
      Вы собираетесь отказаться от выполнения этого задания.<br>
      Это действие плохо скажется на вашем рейтинге и увеличит счетчик проваленных заданий.
    </p>
    <a class="button button--pop-up button--orange">Отказаться</a>
    <div class="button-container">
      <button class="button--close" type="button">Закрыть окно</button>
    </div>
  </div>
</section>

<section class="pop-up pop-up--completion pop-up--close">
  <div class="pop-up--wrapper">
    <h4>Завершение задания</h4>
    <p class="pop-up-text">
      Вы собираетесь отметить это задание как выполненное.
      Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
    </p>
    <div class="completion-form pop-up--form regular-form">
      <?php $form = ActiveForm::begin([
        'action' => [
          'handle',
          'id' => $task->id,
          'actionCodeName' => 'completion'
        ],
        'method' => 'post',
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

      <?= $form->field($reviewModel, 'text_comment')->textarea([
        'id' => 'completion-comment',
      ]) ?>

      <p class="completion-head control-label">Оценка работы</p>

      <div class="stars-rating big active-stars">
        <span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span>
      </div>

      <?= Html::submitInput('Завершить', [
        'class' => 'button button--pop-up button--blue',
      ]) ?>

      <?php ActiveForm::end(); ?>
    </div>
    <div class="button-container">
      <button class="button--close" type="button">Закрыть окно</button>
    </div>
  </div>
</section>

<section class="pop-up pop-up--act_response pop-up--close">
  <div class="pop-up--wrapper">
    <h4>Добавление отклика к заданию</h4>
    <p class="pop-up-text">
      Вы собираетесь оставить свой отклик к этому заданию.
      Пожалуйста, укажите стоимость работы и добавьте комментарий, если необходимо.
    </p>
    <div class="addition-form pop-up--form regular-form">

      <?php $form = ActiveForm::begin([
        'action' => [
          'tasks/handle',
          'id' => $task->id,
          'actionCodeName' => 'act_response'
        ],
        'method' => 'post',
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

      <?= $form->field($responseModel, 'text_comment')->textarea([
        'id' => 'addition-comment',
      ]) ?>

      <?= $form->field($responseModel, 'price')->textInput([
        'id' => 'addition-price',
      ]) ?>


      <?= Html::submitInput('Откликнуться', [
        'class' => 'button button--pop-up button--blue',
      ]) ?>

      <?php ActiveForm::end(); ?>
    </div>
    <div class="button-container">
      <button class="button--close" type="button">Закрыть окно</button>
    </div>
  </div>
</section>
<div class="overlay"></div>