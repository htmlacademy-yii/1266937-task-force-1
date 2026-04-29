<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>

        <?php if (Yii::$app->controller->route !== 'signup/index'): ?>
            <header class="page-header">
                <nav class="main-nav">
                    <a href='<?= Url::to(['tasks/index']) ?>' class="header-logo">
                        <img class="logo-image" src="/img/logotype.png" width=227 height=60 alt="taskforce">
                    </a>
                    <div class="nav-wrapper">
                        <ul class="nav-list">
                            <li class="list-item list-item--active">
                                <a class="link link--nav">Новое</a>
                            </li>
                            <li class="list-item">
                                <a href="#" class="link link--nav">Мои задания</a>
                            </li>
                            <li class="list-item">
                                <a href="<?= Url::to(['tasks/create']) ?>" class="link link--nav">Создать задание</a>
                            </li>
                            <li class="list-item">
                                <a href="#" class="link link--nav">Настройки</a>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="user-block">
                    <a href="#">
                        <img class="user-photo" src="/img/man-glasses.png" width="55" height="55" alt="Аватар">
                    </a>
                    <div class="user-menu">
                        <?php if (!Yii::$app->user->isGuest): ?>
                            <p class="user-name">
                                <?= Yii::$app->user->identity->username ?>
                            </p>
                        <?php endif; ?>
                        <div class="popup-head">
                            <ul class="popup-menu">
                                <li class="menu-item">
                                    <a href="#" class="link">Настройки</a>
                                </li>
                                <li class="menu-item">
                                    <a href="#" class="link">Связаться с нами</a>
                                </li>
                                <li class="menu-item">
                                    <?= Html::a('Выход из системы', ['landing/logout'], [
                                        'data-method' => 'post',
                                        'class' => 'link'
                                    ]) ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>
        <?php endif; ?>

        <?= $content ?>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>