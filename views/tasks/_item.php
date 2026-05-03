<?php

/** @var app\models\Task $task */

use yii\helpers\Url;
use yii\helpers\Html;
?>

<div class="task-card">
  <div class="header-task">
    <a href="<?= Url::to(['tasks/view', 'id' => $task->id]) ?>" class="link link--block link--big">
      <?= Html::encode($task->title) ?>
    </a>
    <p class="price price--task">
      <?= $task->budget ? Html::encode($task->budget) . ' ₽' : '' ?>
    </p>
  </div>
  <p class="info-text">
    <span class="current-time">
      <?= Yii::$app->formatter->asRelativeTime($task->created_at) ?>
    </span>
  </p>
  <p class="task-text">
    <?= Html::encode($task->description) ?>
  </p>
  <div class="footer-task">
    <p class="info-text town-text">
      <?= Html::encode($task->location ?? 'Удалённая работа') ?>
    </p>
    <p class="info-text category-text">
      <?= Html::encode($task->category?->name ?? ' ') ?>
    </p>
    <a href="<?= Url::to(['tasks/view', 'id' => $task->id]) ?>" class="button button--black">Смотреть Задание</a>
  </div>
</div>