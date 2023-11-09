<?php

namespace app\controllers;

use app\models\RegisterSocialForm;
use app\models\Files;
use app\models\Users;
use app\models\Auth;
use Yii;
use yii\web\Controller;
use app\models\LoginForm;
use yii\widgets\ActiveForm;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\authclient\clients\VKontakte;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class LoginController extends Controller
{
    public $layout = 'login';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?']
                    ]
                ]
            ]
        ];
    }


    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();
        $attributes['client'] = $client->getId();

        /* @var $auth Auth */
        $auth = Auth::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // авторизация
                $user = $auth->user;
                Yii::$app->user->login($user);
            } else { // регистрация
                if (isset($attributes['email']) && Users::find()->where(['email' => $attributes['email']])->exists()) {
                    Yii::$app->getSession()->setFlash('error',
                        Yii::t('app', "Пользователь с такой электронной почтой как в {client} уже существует, но с ним не связан.<br> Для начала войдите на сайт используя электронную почту, для того, что бы связать её.", ['client' => $client->getTitle()]),
                    );
                } else {
                    $fullName = ArrayHelper::getValue($attributes, 'first_name') . ' '. ArrayHelper::getValue($attributes, 'last_name');

                    $session = Yii::$app->session;
                    $session->open();
                    $session['userAttributes'] = [
                        'client' => $attributes['client'],
                        'id' => $attributes['id'],
                        'email' => $attributes['email'],
                        'full_name' => $fullName ?? 'Неопознанный Енот',
                        'photo' => ArrayHelper::getValue($attributes, 'photo'),
                        'birthdate' => ArrayHelper::getValue($attributes, 'bdate')
                    ];

                    return Yii::$app->getResponse()->redirect(['login/register-social']);
                }
            }
        } else { // Пользователь уже зарегистрирован
            if (!$auth) { // добавляем внешний сервис аутентификации
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => $attributes['id'],
                ]);
                $auth->save();
            }
        }
    }

    public function actionIndex()
    {
        $model = new LoginForm();

        if (\Yii::$app->request->getIsPost()) {
            $model->load(\Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($model);
            }

            if ($model->validate()) {
                \Yii::$app->user->login($model->getUser());

                return $this->goHome();
            }
        }

        return $this->render('index', compact('model'));
    }

    public function actionRegisterSocial()
    {
        $model = new RegisterSocialForm();

        if (\Yii::$app->request->getIsPost()) {
            $model->load(\Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($model);
            }

            if ($model->register()) {
                $this->goHome();
            }
        }

        return $this->render('register-social', compact('model'));
    }
}