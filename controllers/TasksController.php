<?php

namespace app\controllers;

use app\models\Categories;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\models\TasksSearch;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $categories = Categories::find()->asArray()->all();
        $categories = ArrayHelper::map($categories, 'id', 'name');

        $searchModel = new TasksSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->post());

        $tasks = $dataProvider->getModels();

        return $this->render('index', compact('searchModel','tasks', 'categories'));
    }
}