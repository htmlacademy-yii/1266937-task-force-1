<?php

/** @var app\models\Response $model */

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="response-card">
  <img class="customer-photo" src="<?= Url::to('@web/' . ($model->contractor->avatar->url ?? 'img/man-sweater.png')) ?>"
    width="146" height="156" alt="Фото заказчиков">
  <div class="feedback-wrapper">
    <a href="#" class="link link--block link--big"><?= Html::encode($model->contractor->username) ?></a>
    <div class="response-wrapper">
      <div class="stars-rating small"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span
          class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
      <p class="reviews">2 отзыва</p>
    </div>
    <p class="response-message">
      <?= Html::encode($model->text_comment) ?>
    </p>
  </div>
  <div class="feedback-wrapper">
    <p class="info-text">
      <span class="current-time"><?= Yii::$app->formatter->asRelativeTime($model->created_at) ?></span>
    </p>
    <p class="price price--small"><?= $model->price ? Html::encode($model->price) . ' ₽' : '' ?></p>
  </div>

  <div class="button-popup">
    <a href="#" class="button button--blue button--small">Принять</a>
    <a href="#" class="button button--orange button--small">Отказать</a>
  </div>

</div>