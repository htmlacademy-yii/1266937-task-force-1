<?php

/** @var app\models\Response $model */
/** @var app\models\Task $task */
/** @var app\models\User $user */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\ButtonActionWidget;
use app\services\actions\AbstractAction;
use app\components\UserRatingWidget;

?>

<div class="response-card">
  <img class="customer-photo"
    src="<?= Url::to('@web' . ($model->contractor->avatar?->url ?? '/img/man-glasses.png')) ?>" width="146" height="156"
    alt="Фото заказчиков">
  <div class="feedback-wrapper">
    <a href="#" class="link link--block link--big"><?= Html::encode($model->contractor->username ?? 'Аноним') ?></a>
    <div class="response-wrapper">

      <?= UserRatingWidget::widget([
        'model' => $model->contractor,
        'attribute' => 'rating',
        'readOnly' => true,
        'showValue' => false,
        'sizeClass' => 'small',
      ]); ?>

      <p class="reviews">
        <?= Yii::t('app', '{n, plural, =0{нет отзывов} one{# отзыв} few{# отзыва} many{# отзывов} other{# отзыва}}', [
          'n' => $model->contractor->reviewsCount
        ]) ?>
      </p>
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
    <?= ButtonActionWidget::widget([
      'task' => $task,
      'response' => $model,
      'type' => AbstractAction::TYPE_RESPONSE,
    ]) ?>
  </div>

</div>