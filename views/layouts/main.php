<?php

/** @var yii\web\View $this */
/** @var string $content */
/** @var array $user */

use app\assets\AppAsset;
use app\models\Users;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\widgets\Menu;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

$user = Users::findIdentity(\Yii::$app->user->getId());
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

<header class="page-header">
    <?php
    echo Html::beginTag('nav', ['class' => 'main-nav']);
        $logoImage = Html::img('@web/img/logotype.png', ['alt' => Yii::$app->name, 'class' => 'logo-image', 'width' => 227, 'height' => 60]);
        echo Html::a($logoImage, Yii::$app->homeUrl, ['class' => 'header-logo']);

        echo Html::beginTag('div', ['class' => 'nav-wrapper']);
            $menuBar = [
                [
                        'label' => 'Новое',
                        'url' => [Yii::$app->homeUrl],
                        'active' => get_class(Yii::$app->controller) === \app\controllers\TasksController::class
                ],
                [
                        'label' => 'Мои задания',
                        'url' => ['/my-task'],
                        'active' => get_class(Yii::$app->controller) === \app\controllers\MyTaskController::class
                ],
            ];
            if (Yii::$app->user->can('client')) {
                $menuBar[] = ['label' => 'Создать задание', 'url' => ['add-task/index']];
            }
            $menuBar[] = ['label' => 'Настройки', 'url' => ['profile/settings']];
            echo Menu::widget([
                'items' => $menuBar,
                'options' => [
                    'class' => 'nav-list'
                ],
                'itemOptions' => [
                    'class' => 'list-item'
                ],
                'activeCssClass'=>'list-item--active',
                'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>'
            ]);
        echo Html::endTag('div');
    echo Html::endTag('nav');

    if(!Yii::$app->user->isGuest) {
        echo Html::beginTag('div', ['class' => 'user-block']);
            $userAvatar = Html::img($user->avatarPath, ['alt' => $user->full_name, 'class' => 'user-photo', 'width' => 55, 'height' => 55]);
            echo Html::a($userAvatar, ['user/view', 'id' => $user->id]);

            echo Html::beginTag('div', ['class' => 'user-menu']);
                echo Html::tag('p', $user->full_name, ['class' => 'user-name']);

                echo Html::beginTag('div', ['class' => 'popup-head']);
                    echo Menu::widget([
                        'items' => [
                            ['label' => 'Настройки', 'url' => ['profile/settings']],
                            ['label' => 'Связаться с нами', 'url' => '#'],
                            ['label' => 'Выход из системы', 'url' => ['tasks/logout']],
                        ],
                        'options' => [
                            'class' => 'popup-menu'
                        ],
                        'itemOptions' => [
                            'class' => 'menu-item'
                        ],
                        'activeCssClass'=>'list-item--active',
                        'linkTemplate' => '<a href="{url}" class="link">{label}</a>'
                    ]);
                echo Html::endTag('div');
            echo Html::endTag('div');
        echo Html::endTag('div');
    }
    ?>
</header>

<?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
