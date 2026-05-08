<?php

use yii\helpers\Url;

$action = Yii::$app->controller->action->id;
?>

<div class="left-menu left-menu--edit">
  <h3 class="head-main head-task">Настройки</h3>
  <ul class="side-menu-list">
    <li class="side-menu-item <?= $action === 'index' ? 'side-menu-item--active' : '' ?>">
      <a href="<?= Url::to(['settings/index']) ?>" class="link link--nav">Мой профиль</a>
    </li>
    <li class="side-menu-item <?= $action === 'security' ? 'side-menu-item--active' : '' ?>">
      <a href="<?= Url::to(['settings/security']) ?>" class="link link--nav">Безопасность</a>
    </li>
  </ul>
</div>