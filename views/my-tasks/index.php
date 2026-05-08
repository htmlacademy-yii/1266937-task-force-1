<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $tasksDataProvider */
/** @var array $menuItems */
/** @var string $currentStatus */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Мои задания';

?>

<main class="main-content container">
  <div class="left-menu">
    <h3 class="head-main head-task">Мои задания</h3>
    <ul class="side-menu-list">
      <?php foreach ($menuItems as $item => $label): ?>
        <li class="side-menu-item <?= $currentStatus === $item ? 'side-menu-item--active' : '' ?>">
          <a href="<?= Url::to(['my-tasks/index', 'status' => $item]) ?>" class="link link--nav">
            <?= Html::encode($label) ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <div class="left-column left-column--task">
    <h3 class="head-main head-regular"><?= Html::encode($menuItems[$currentStatus] ?? 'Мои задания') ?></h3>

    <?php foreach ($tasksDataProvider->getModels() as $task): ?>
      <?= $this->render('/tasks/_item', ['task' => $task]) ?>
    <?php endforeach; ?>
  </div>
</main>