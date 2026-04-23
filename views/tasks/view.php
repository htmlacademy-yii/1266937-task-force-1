<?php

/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\Task $task */

use yii\helpers\Html;
use yii\widgets\ListView;

?>

<main class="main-content container">
  <div class="left-column">
    <div class="head-wrapper">
      <h3 class="head-main"><?= Html::encode($task->title) ?></h3>
      <p class="price price--big"><?= Html::encode($task->budget) . '&nbsp;₽' ?></p>
    </div>
    <p class="task-description"><?= Html::encode($task->description) ?></p>

    <a href="#" class="button button--blue action-btn" data-action="act_response">Откликнуться на задание</a>
    <a href="#" class="button button--orange action-btn" data-action="refusal">Отказаться от задания</a>
    <a href="#" class="button button--pink action-btn" data-action="completion">Завершить задание</a>

    <div class="task-map">
      <img class="map" src="img/map.png" width="725" height="346" alt="Новый арбат, 23, к. 1">
      <p class="map-address town">Москва</p>
      <p class="map-address">Новый арбат, 23, к. 1</p>
    </div>

    <h4 class="head-regular">Отклики на задание</h4>

    <?= ListView::widget(
      [
        'dataProvider' => $responsesDataProvider,
        'itemView' => '_response',
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
        <dd><?= Yii::$app->formatter->asDatetime($task->deadline_at, 'php:j F, H:i') ?></dd>
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