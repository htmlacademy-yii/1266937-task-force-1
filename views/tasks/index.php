<?php

/** @var yii\data\ActiveDataProvider $tasksDataProvider */
/** @var app\models\TaskSearch $searchModel */
/** @var array $categories */

use yii\widgets\LinkPager;

?>

<main class="main-content container">
  <div class="left-column">
    <h3 class="head-main head-task">Новые задания</h3>

    <?php foreach ($tasksDataProvider->getModels() as $task): ?>
      <?= $this->render('_item', ['task' => $task]) ?>
    <?php endforeach; ?>

    <div class="pagination-wrapper">
      <?= LinkPager::widget([
        'pagination' => $tasksDataProvider->getPagination(),
        'options' => ['class' => 'pagination-list'],
        'linkContainerOptions' => ['class' => 'pagination-item'],
        'linkOptions' => ['class' => 'link link--page'],
        'activePageCssClass' => 'pagination-item--active',
        'disableCurrentPageButton' => false,
        'prevPageCssClass' => 'pagination-item mark',
        'nextPageCssClass' => 'pagination-item mark',
        'disabledPageCssClass' => 'disabled',
        'prevPageLabel' => '',
        'nextPageLabel' => '',
        'registerLinkTags' => false,
        'hideOnSinglePage' => true,
      ]) ?>
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