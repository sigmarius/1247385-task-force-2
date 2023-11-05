<?php

namespace app\controllers;

use app\models\Categories;
use app\models\EditProfileForm;
use app\models\Users;
use Yii;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class ProfileController extends BaseAuthController
{
    public function actionSettings()
    {
        /** @var Users $user*/
        $user = Users::findModel(Yii::$app->user->identity->id);
        $categories = Categories::getAllCategoriesNames();

        $model = new EditProfileForm($user);
        $model->scenario = EditProfileForm::SCENARIO_PROFILE_SETTINGS;

        if (Yii::$app->request->getIsPost()) {
            $model->load(Yii::$app->request->post());
            $model->avatar = UploadedFile::getInstance($model, 'avatar');

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($model);
            }

            if ($model->updateProfile()) {
                return $this->redirect(['user/view', 'id' => $user->id]);
            }
        }

        return $this->render('settings', compact('model', 'categories'));
    }
}