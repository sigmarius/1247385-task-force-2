<?php

namespace app\models;

use yii\base\Model;
use yii\db\ActiveRecord;

class RegistrationForm extends Users
{
    public $password_repeat;
    public $is_worker;

    public function attributeLabels()
    {
        return [
            'full_name' => 'Ваше имя',
            'email' => 'Email',
            'city_id' => 'Город',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
            'is_worker' => 'я собираюсь откликаться на заказы',
        ];
    }

    public function rules()
    {
        return [
            [['full_name', 'email', 'city_id', 'password', 'password_repeat', 'is_worker'], 'safe'],
            [['full_name', 'email', 'city_id', 'password', 'password_repeat'], 'required'],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['full_name'], 'string', 'min' => 3],
            [['password'], 'string', 'min' => 8],
            ['password', 'compare'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' => ['city_id' => 'id'], 'message' => 'Выберите город из списка'],
        ];
    }
}