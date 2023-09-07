<?php

use app\models\TasksSearch;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var array $dataProvider */
/** @var array $categories */
/** @var TasksSearch $searchModel */

$this->title = 'Taskforce';
?>
<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main head-task">Новые задания</h3>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'task-card'],
            'itemView' => '_task',
            'layout' => '{items}{pager}',
            'pager' => [
                'activePageCssClass' => 'pagination-item--active',
                'prevPageCssClass' => 'pagination-item mark',
                'nextPageCssClass' => 'pagination-item mark',
                'pageCssClass' => 'pagination-item',
                'registerLinkTags' => true,
                'prevPageLabel' => '',
                'nextPageLabel' => '',
                'options' => [
                    'class' => 'pagination-list'
                ],
                'linkOptions' => [
                        'class' => 'link link--page'
                ]
            ],
        ]) ?>
    </div>
    <div class="right-column">
        <div class="right-card black">
            <div class="search-form">
                <?php $form = ActiveForm::begin([
                    'method' => 'post'
                ]); ?>
                <h4 class="head-card">Категории</h4>
                <div class="form-group">
                    <div class="checkbox-wrapper">
                        <?= $form->field($searchModel, 'categories', [
                                'options' => ['tag' => false, 'unselect' => null]
                        ])
                            ->checkboxList($categories, [
                                    'tag' => false,
                                    'itemOptions' => [
                                        'labelOptions' => [
                                            'class' => 'control-label'
                                        ],
                                    ]
                            ])->label(false);
                        ?>
                    </div>
                </div>

                <h4 class="head-card">Дополнительно</h4>
                <?=$form->field($searchModel, 'remoteWork', [
                        'template' => "{label}\n{input}",
                        'options' => [
                            'class' => 'form-group',

                ]])->checkbox([
                        'label' => 'Удаленная работа',
                        'labelOptions' => [
                            'class' => 'control-label'
                        ]
                ])->label(false); ?>
                <?=$form->field($searchModel, 'withoutReactions', [
                        'template' => "{label}\n{input}",
                        'options' => ['class' => 'form-group']
                ])->checkbox([
                    'label' => 'Без откликов',
                    'labelOptions' => [
                        'class' => 'control-label'
                    ]
                ])->label(false); ?>

                <h4 class="head-card">Период</h4>
                <?=$form->field($searchModel, 'hoursPeriod', [
                    'template' => "{label}\n{input}",
                    'options' => ['class' => 'filters-block__field field field--select field--small']
                ])->dropDownList([
                        '' => 'Выберите интервал',
                        1 => '1 час',
                        12 => '12 часов',
                        24 => '24 часа'
                ])->label(false); ?>

                <?= Html::submitInput('Искать', ['class' => 'button button--blue']); ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</main>