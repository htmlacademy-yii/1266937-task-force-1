<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\Task $task */
/** @var app\models\Response $responseModel */
/** @var app\models\Review $reviewModel */

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\components\ButtonActionWidget;
use \app\services\actions\AbstractAction;
use app\components\UserRatingWidget;

$this->title = 'Просмотр задания';

$apiKey = Yii::$app->params['geocoderApiKey'];
$this->registerJsFile("//api-maps.yandex.ru/2.1/?apikey={$apiKey}&lang=ru_RU", ['position' => \yii\web\View::POS_HEAD]);

if ($task->latitude && $task->longitude) {
  $js = <<<JS
        ymaps.ready(function () {
            var myMap = new ymaps.Map("map", {
                center: [{$task->latitude}, {$task->longitude}],
                zoom: 14
            });
            myMap.geoObjects.add(new ymaps.Placemark([{$task->latitude}, {$task->longitude}]));
        });
    JS;

  $this->registerJs($js);
}

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

    <?php if ($task->latitude && $task->longitude): ?>
      <div class="task-map">
        <div id="map" class="map" style="width: 725px; height: 346px;"></div>
        <p class="map-address town">
          <?= Html::encode($task->city?->name ?? 'Удалённая работа') ?>
        </p>
        <p class="map-address">
          <?php
          $location = (string) $task->location;
          $parts = explode(',', $location, 2);
          $displayAddress = isset($parts[1]) ? trim($parts[1]) : ($parts[0] ?: 'Удалённая работа');
          echo Html::encode($displayAddress);
          ?>
        </p>
      </div>
    <?php endif; ?>

    <?php if ($responsesDataProvider->getTotalCount() > 0): ?>
      <h4 class="head-regular">Отклики на задание</h4>

      <?= ListView::widget([
        'dataProvider' => $responsesDataProvider,
        'itemView' => '_response',
        'viewParams' => [
          'task' => $task
        ],
        'layout' => '{items}',
        'options' => ['tag' => false],
        'itemOptions' => ['tag' => false],
      ]) ?>
    <?php endif; ?>

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

        <?php foreach ($task->files as $file): ?>
          <li class="enumeration-item">
            <a href="<?= Html::encode($file->url) ?>" class="link link--block link--clip" download>
              <?= Html::encode($file->name) ?>
            </a>
            <p class="file-size">
              <?= str_replace('КиБ', 'Кб', Yii::$app->formatter->asShortSize($file->size, 0)) ?>
            </p>
          </li>
        <?php endforeach; ?>

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
    <a href="<?= Url::to(['tasks/handle', 'id' => $task->id, 'actionCodeName' => 'refusal']) ?>"
      class="button button--pop-up button--orange" data-method="post">Отказаться</a>
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

      <?= $form->field($reviewModel, 'rating', [
        'options' => ['class' => 'form-group'],
        'template' => "{input}\n{error}",
        'errorOptions' => ['tag' => 'span', 'class' => 'help-block']
      ])->widget(UserRatingWidget::class, [
            'sizeClass' => 'big',
            'readOnly' => false
          ])->label(false) ?>

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