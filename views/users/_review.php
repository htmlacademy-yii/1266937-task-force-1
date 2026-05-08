<?php

/** @var app\models\Review $model */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\UserRatingWidget;

?>

<div class="response-card">

  <?php
  $avatarUrl = '/img/man-glasses.png';
  if ($model->customer && $model->customer->avatar) {
    $avatarUrl = $model->customer->avatar->url;
  }
  ?>

  <img class="customer-photo" src="<?= Url::to('@web' . $avatarUrl) ?>" width="120" height="127" alt="Фото заказчиков">
  <div class="feedback-wrapper">
    <p class="feedback">«<?= Html::encode($model->text_comment) ?>»</p>
    <p class="task">Задание «<a href="#" class="link link--small"><?= Html::encode($model->task->title) ?></a>»
      выполнено</p>
  </div>
  <div class="feedback-wrapper">
    <?php if (isset($model->rating)): ?>
      <?= UserRatingWidget::widget([
        'model' => $model,
        'attribute' => 'rating',
        'sizeClass' => 'small',
        'readOnly' => true,
      ]); ?>
    <?php endif; ?>

    <p class="info-text">
      <span class="current-time">
        <?= ($model->created_at && $model->created_at !== '0000-00-00 00:00:00')
          ? Yii::$app->formatter->asRelativeTime($model->created_at)
          : 'недавно' ?>
      </span>
    </p>
  </div>
</div>