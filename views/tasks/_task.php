<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var \app\models\Tasks $model */
?>

<div class="header-task">
    <a href="<?= Url::to(['tasks/view', 'id' => $model->id]); ?>" class="link link--block link--big">
        <?= $model->title; ?>
    </a>
    <p class="price price--task">
        <?= $model->price; ?>&nbsp;₽
    </p>
</div>
<p class="info-text">
    <span class="current-time">
        <?= $model->getPublishedTimePassed(); ?>
    </span></p>
<p class="task-text"><?= $model->description; ?>
</p>
<div class="footer-task">
    <?php if(!empty($model->city)): ?>
        <p class="info-text town-text"><?= $model->city->name; ?></p>
    <?php endif; ?>
    <p class="info-text category-text"><?= $model->category->name; ?></p>
    <a href="<?= Url::to(['tasks/view', 'id' => $model->id]); ?>" class="button button--black">Смотреть Задание</a>
</div>

