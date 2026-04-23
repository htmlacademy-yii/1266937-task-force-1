<?php

/** @var app\models\Review $model */

use yii\helpers\Html;

?>

<div class="response-card">
  <img class="customer-photo" src="img/man-coat.png" width="120" height="127" alt="Фото заказчиков">
  <div class="feedback-wrapper">
    <p class="feedback">«<?= Html::encode($model->text_comment) ?>»</p>
    <p class="task">Задание «<a href="#" class="link link--small"><?= Html::encode($model->task->title) ?></a>»
      выполнено</p>
  </div>
  <div class="feedback-wrapper">
    <div class="stars-rating small"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span
        class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
    <p class="info-text">
      <span class="current-time"><?= Yii::$app->formatter->asRelativeTime($model->created_at) ?></span>
    </p>
  </div>
</div>