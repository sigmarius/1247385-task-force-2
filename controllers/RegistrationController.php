<?php

namespace app\controllers;

use app\models\RegistrationForm;
use Yii;
use app\models\Users;
use app\models\Cities;
use yii\web\Controller;

class RegistrationController extends Controller
{
    public function actionIndex()
    {
        $model = new RegistrationForm();

        $cities = Cities::getAllCityNames();

        if (Yii::$app->request->getIsPost()) {
            $model->load(Yii::$app->request->post());

            if ($model->validate()) {
                $model->generateSafePassword($model->password);
                $model->generateAuthKey();

                $model->save(false);

                $role = empty($model->is_worker) ? 'client' : 'worker';

                $auth = Yii::$app->authManager;
                $userRole = $auth->getRole($role);
                $auth->assign($userRole, $model->getId());

                $this->goHome();
            }
        }

        return $this->render('index', compact('model', 'cities'));
    }
}