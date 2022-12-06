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
use app\models\Categories;

class TestController extends Controller
{
    public function actionIndex()
    {
        $categories = Categories::find()->all();

        if (!$categories) {
            throw new NotFoundHttpException('Нет записей в таблице');
        }

        return $this->render('index', ['categories' => $categories]);
    }
}