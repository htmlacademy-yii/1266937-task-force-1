<?php

/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\User $user */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

?>

<main class="main-content container">

  <div class="left-column">
    <h3 class="head-main"><?= Html::encode($user->username) ?></h3>
    <div class="user-card">
      <div class="photo-rate">
        <img class="card-photo" src="/<?= Html::encode($user->avatar->url ?? 'img/avatars/1.png') ?>" width="191"
          height="190" alt="Фото пользователя">
        <div class="card-rate">
          <div class="stars-rating big"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span
              class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
          <span class="current-rate">4.23</span>
        </div>
      </div>
      <p class="user-description">
        <?= $user->profile_info ? Html::encode($user->profile_info) : 'Не рассказал о себе' ?>
      </p>
    </div>

    <div class="specialization-bio">
      <div class="specialization">
        <p class="head-info">Специализации</p>
        <?php foreach ($user->categories as $category): ?>
          <ul class="special-list">
            <li class="special-item">
              <a href="<?= Url::to(['tasks/index', 'category' => $category->id]) ?>" class="link link-regular">
                <?= Html::encode($category->name) ?>
              </a>
            </li>
          </ul>
        <?php endforeach; ?>
      </div>

      <div class="bio">
        <p class="head-info">Био</p>
        <p class="bio-info">
          <span class="country-info">Россия</span>,
          <span class="town-info"><?= Html::encode($user->city->name) ?></span>,
          <?php if (!empty($user->age)): ?>
            <span class="age-info"><?= Html::encode($user->age) ?></span> лет
          <?php endif; ?>
        </p>
      </div>

    </div>

    <h4 class="head-regular">Отзывы заказчиков</h4>

    <?= ListView::widget(
      [
        'dataProvider' => $reviewsDataProvider,
        'itemView' => '_review',
        'layout' => '{items}',
        'options' => ['tag' => false],
        'itemOptions' => ['tag' => false],
      ]
    )
      ?>

  </div>

  <div class="right-column">

    <div class="right-card black">
      <h4 class="head-card">Статистика исполнителя</h4>
      <dl class="black-list">
        <dt>Всего заказов</dt>
        <dd><?= $user->completedTasksCount ?> выполнено, <?= $user->failedTasksCount ?> провалено</dd>
        <dt>Место в рейтинге</dt>
        <dd>25 место</dd>
        <dt>Дата регистрации</dt>
        <dd><?= Yii::$app->formatter->asDatetime($user->created_at, 'php:j F, H:i') ?></dd>
        <dt>Статус</dt>
        <?php if ($user->hasActiveTask()): ?>
          <dd>Занят</dd>
        <?php else: ?>
          <dd>Открыт для новых заказов</dd>
        <?php endif; ?>

      </dl>
    </div>

    <div class="right-card white">
      <h4 class="head-card">Контакты</h4>
      <ul class="enumeration-list">
        <li class="enumeration-item">
          <a href="tel:<?= preg_replace('/\D/', '', $user->phone ?? '') ?>" class="link link--block link--phone">
            <?= Html::encode($user->phone ?? '') ?>
          </a>

        </li>
        <li class="enumeration-item">
          <a href="mailto:<?= Html::encode($user->email) ?>" class="link link--block link--email">
            <?= Html::encode($user->email) ?>
          </a>
        </li>
        <li class="enumeration-item">
          <a href="https://t.me/<?= ltrim($user->telegram ?? '', '@') ?>"
            class="link link--block link--tg"><?= Html::encode($user->telegram) ?>
          </a>
        </li>
      </ul>
    </div>
  </div>
</main>