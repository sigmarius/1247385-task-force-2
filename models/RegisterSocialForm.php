<?php

namespace app\models;

use app\models\Users;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * RegisterSocialForm is the model behind the login social form.
 *
 * @property-read Users|null $user
 *
 */
class RegisterSocialForm extends Model
{
    public $location;
    public $latitude;
    public $longitude;

    public $is_worker;

    protected $cityId;
    protected $userRole;

    public function attributeLabels()
    {
        return [
            'location' => 'Мой город',
            'is_worker' => 'Я собираюсь откликаться на заказы',
        ];
    }


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['location'], 'required',
                'when' => fn() => empty($this->latitude) || empty($this->longitude),
                'whenClient' => "function (attribute, value) {
                    if ($('#latitude').val() == '' || $('#longitude').val() == '') {
                        $('#location').val('')
                    }
                }",
                'message' => 'Пожалуйста выберите город из списка автоподстановки'
            ],
            [['location'], 'string', 'min' => 2],
            [['latitude', 'longitude'], 'safe'],
            ['is_worker', 'boolean'],
            ['is_worker', 'default', 'value' => null],
        ];
    }


    /**
     * Finds cityId by [[location]]
     *
     * @return int
     */
    public function getCityId()
    {
        $city = [
            'name' => $this->location,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];

        $this->cityId = Cities::findCityIdByName($city);

        return $this->cityId;
    }

    /**
     * Finds userRole by [[is_worker]]
     *
     * @return int
     */
    public function getUserRole()
    {
        $this->userRole = empty($this->is_worker) ? 'client' : 'worker';
        return $this->userRole;
    }

    public function register() {
        if (!$this->validate()) {
            return false;
        }

        $session = Yii::$app->session;
        $attributes = $session->get('userAttributes');

        if (!$attributes) {
            return false;
        }

        $password = Yii::$app->security->generateRandomString(8);

        $user = new Users([
            'full_name' => $attributes['full_name'],
            'email' => $attributes['email'],
        ]);

        $user->generateSafePassword($password);
        $user->generateAuthKey();

        $photo = ArrayHelper::getValue($attributes, 'photo');
        if (!empty($photo)) {
            $avatar = new Files();
            $avatar->file_path = $photo;
            $result = $avatar->save();

            if ($result) {
                $user->avatar_id = $avatar->id;
            }
        }

        $birthdate = $attributes['birthdate'];
        if (!empty($birthdate)) {
            $user->birthdate = (new \DateTime($birthdate, new \DateTimeZone('Europe/Moscow')))->format('Y-m-d H:i:s');
        }

        $user->city_id = $this->getCityId();

        $transaction = $user->getDb()->beginTransaction();
        if ($user->save()) {
            $role = $this->getUserRole();

            $auth = Yii::$app->authManager;
            $userRole = $auth->getRole($role);
            $auth->assign($userRole, $user->getId());

            $auth = new Auth([
                'user_id' => $user->id,
                'source' => $attributes['client'],
                'source_id' => (string)$attributes['id'],
            ]);
            if ($auth->save()) {
                $transaction->commit();
                $session->remove('userAttributes');
                Yii::$app->user->login($user);
                return true;
            } else {
                print_r($auth->getErrors());
            }
        } else {
            print_r($user->getErrors());
        }
    }
}
