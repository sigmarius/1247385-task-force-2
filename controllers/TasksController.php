<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Tasks;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $tasks = Tasks::find()
            ->where(['current_status' => 'new'])
            ->joinWith('city')
            ->joinWith('category')
            ->orderBy('published_at DESC')
            ->all();

        return $this->render('index', compact('tasks'));
    }
}