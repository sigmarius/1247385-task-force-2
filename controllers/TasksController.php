<?php

namespace app\controllers;

use app\models\Categories;
use app\models\Tasks;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\models\TasksSearch;
use Yii;
use yii\web\NotFoundHttpException;

class TasksController extends Controller
{
    public function actionIndex($id = null)
    {
        $categories = Categories::find()->asArray()->all();
        $categories = ArrayHelper::map($categories, 'id', 'name');

        $searchModel = new TasksSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->post(), $id);

        $tasks = $dataProvider->getModels();

        return $this->render('index', compact('searchModel','tasks', 'categories'));
    }

    public function actionView($id)
    {
        $task = Tasks::findOne((int)$id);

        if (!$task) {
            throw new NotFoundHttpException("Задание с ID $id не найдено");
        }

        $reactions = [];

        foreach ($task->reactions as $key => $reaction) {
            $feedbacksCount = $reaction->worker->getWorkerFeedbacks()->count();

            $reactions[$key] = [
                'user_id' => $reaction->worker->id,
                'img' => $reaction->worker->avatar->file_path,
                'name' => $reaction->worker->full_name,
                'rating' => floor($reaction->worker->workerRating),
                'feedbacks_count' => Yii::$app->i18n->format(
                    '{n, plural, =0{нет отзывов} =1{один отзыв} one{# отзыв} few{# отзыва} many{# отзывов} other{# отзывов}}',
                    ['n' => $feedbacksCount],
                    'ru_RU'
                ),
                'comment' => $reaction->comment,
                'price' => $reaction->worker_price,
                'published' => $reaction->getPublishedTimePassed(),
            ];
        }

        return $this->render('view', compact('task', 'reactions'));
    }
}