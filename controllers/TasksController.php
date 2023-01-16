<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Tasks;
use Taskforce\Logic\Task;
use Taskforce\Service;

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