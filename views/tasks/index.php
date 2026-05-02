<?php

/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\helpers\Html;
use yii\helpers\Url;

$tasks = $tasksDataProvider->getModels();

?>

<main class="main-content container">
  <div class="left-column">
    <h3 class="head-main head-task">Новые задания</h3>

    <?php foreach ($tasks as $task): ?>
      <div class="task-card">
        <div class="header-task">
          <a href="<?= Url::to(['tasks/view', 'id' => $task->id]) ?>"
            class="link link--block link--big"><?= Html::encode($task->title) ?></a>
          <p class="price price--task"><?= $task->budget ? Html::encode($task->budget) . ' ₽' : '' ?> </p>
        </div>
        <p class="info-text">
          <span class="current-time"><?= Yii::$app->formatter->asRelativeTime($task->created_at) ?></span>
        </p>
        <p class="task-text"><?= Html::encode($task->description) ?></p>
        <div class="footer-task">
          <p class="info-text town-text"><?= Html::encode($task->location ?? 'Удалённая работа') ?></p>
          <p class="info-text category-text"><?= Html::encode($task->category->name ?? '') ?></p>
          <a href="<?= Url::to(['tasks/view', 'id' => $task->id]) ?>" class="button button--black">Смотреть Задание</a>
        </div>
      </div>
    <?php endforeach; ?>

    <div class="pagination-wrapper">
      <ul class="pagination-list">
        <li class="pagination-item mark">
          <a href="#" class="link link--page"></a>
        </li>
        <li class="pagination-item">
          <a href="#" class="link link--page">1</a>
        </li>
        <li class="pagination-item pagination-item--active">
          <a href="#" class="link link--page">2</a>
        </li>
        <li class="pagination-item">
          <a href="#" class="link link--page">3</a>
        </li>
        <li class="pagination-item mark">
          <a href="#" class="link link--page"></a>
        </li>
      </ul>
    </div>
  </div>
  <div class="right-column">
    <div class="right-card black">
      <?= $this->render('_search', [
        'model' => $searchModel,
        'categories' => $categories
      ]) ?>
    </div>
  </div>
</main>