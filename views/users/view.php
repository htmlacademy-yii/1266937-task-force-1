<?php
/** @var yii\web\View $this */
/** @var \app\models\User $user */
/** @var \yii\data\ActiveDataProvider $reviewsDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use app\components\UserRatingWidget;

$this->title = 'Профиль';
?>

<main class="main-content container">
  <div class="left-column">
    <h3 class="head-main"><?= Html::encode($user->username) ?></h3>
    <div class="user-card">
      <div class="photo-rate">
        <?php
        $avatarUrl = ($user->avatar && $user->avatar->url) ? $user->avatar->url : 'img/man-glasses.jpg';
        ?>
        <img class="card-photo" src="<?= Url::to('@web/' . $avatarUrl) ?>" width="191" height="190"
          alt="Фото пользователя">

        <?= UserRatingWidget::widget([
          'model' => $user,
          'attribute' => 'rating',
          'readOnly' => true,
          'showValue' => true,
          'sizeClass' => 'big'
        ]); ?>
      </div>
      <p class="user-description">
        <?= $user->profile_info ? Html::encode($user->profile_info) : 'Пользователь не указал информацию о себе' ?>
      </p>
    </div>

    <div class="specialization-bio">
      <div class="specialization">
        <p class="head-info">Специализации</p>
        <ul class="special-list">
          <?php if (!empty($user->categories)): ?>
            <?php foreach ($user->categories as $category): ?>
              <li class="special-item">
                <a href="<?= Url::to(['tasks/index', 'category_id' => $category->id]) ?>" class="link link--regular">
                  <?= Html::encode($category->name) ?>
                </a>
              </li>
            <?php endforeach; ?>
          <?php else: ?>
            <li class="special-item">Специализации не указаны</li>
          <?php endif; ?>
        </ul>
      </div>

      <div class="bio">
        <p class="head-info">Био</p>
        <p class="bio-info">
          <span class="country-info">Россия</span>,
          <span class="town-info"><?= Html::encode($user->city->name ?? 'Не указан') ?></span>
          <?php if ($user->age): ?>
            <span class="age-info">,
              <?= $user->age ?>
              <?= Yii::$app->i18n->format('{n, plural, one{год} few{года} many{лет} other{лет}}', ['n' => $user->age], 'ru-RU') ?>
            </span>
          <?php endif; ?>
        </p>
      </div>
    </div>

    <?php if (isset($reviewsDataProvider) && $reviewsDataProvider->getTotalCount() > 0): ?>
      <h4 class="head-regular">Отзывы заказчиков</h4>
      <?= ListView::widget([
        'dataProvider' => $reviewsDataProvider,
        'itemView' => '_review',
        'layout' => '{items}',
        'options' => ['tag' => false],
        'itemOptions' => ['tag' => false],
      ]) ?>
    <?php endif; ?>
  </div>

  <div class="right-column">
    <div class="right-card black">
      <h4 class="head-card">Статистика исполнителя</h4>
      <dl class="black-list">
        <dt>Всего заказов</dt>
        <dd><?= (int) $user->completedTasksCount ?> выполнено, <?= (int) $user->failedTasksCount ?> провалено</dd>

        <dt>Место в рейтинге</dt>
        <dd><?= $user->getRank() ?> место</dd>

        <dt>Дата регистрации</dt>
        <dd><?= Yii::$app->formatter->asDatetime($user->created_at, 'php:j F, H:i') ?></dd>

        <dt>Статус</dt>
        <dd><?= $user->hasActiveTask() ? 'Занят' : 'Открыт для новых заказов' ?></dd>
      </dl>
    </div>

    <div class="right-card white">
      <h4 class="head-card">Контакты</h4>
      <ul class="enumeration-list">
        <?php if ($user->phone): ?>
          <li class="enumeration-item">
            <a href="tel:<?= Html::encode($user->phone) ?>"
              class="link link--block link--phone"><?= Html::encode($user->phone) ?></a>
          </li>
        <?php endif; ?>
        <li class="enumeration-item">
          <a href="mailto:<?= Html::encode($user->email) ?>"
            class="link link--block link--email"><?= Html::encode($user->email) ?></a>
        </li>
        <?php if ($user->telegram): ?>
          <li class="enumeration-item">
            <a href="https://t.me<?= ltrim($user->telegram, '@') ?>"
              class="link link--block link--tg"><?= Html::encode($user->telegram) ?></a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</main>