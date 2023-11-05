<?php

namespace app\models;

use LogicException;
use Taskforce\Service\Helpers\ImageHelper;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class EditProfileForm extends Model
{
    const SCENARIO_PROFILE_SETTINGS = 'profile-settings';
    const SCENARIO_SECURITY_SETTINGS = 'security-settings';

    public $full_name;
    public $email;
    public $birthdate;
    public $phone;
    public $telegram;
    public $about;
    public $avatar_id;

    public $avatar;
    public $avatarPath;

    public $specialities;

    public $oldPassword;
    public $password;
    public $password_repeat;

    /**
     * @var Users
     */
    private $_user;

    public function __construct(Users $user, $config = [])
    {
        $this->_user = $user;

        $this->full_name = $user->full_name;
        $this->email = $user->email;
        $this->birthdate = $user->birthdate;
        $this->phone = $user->phone;
        $this->telegram = $user->telegram;
        $this->about = $user->about;
        $this->specialities = ArrayHelper::getColumn($user->userCategories, 'category_id');
        $this->avatarPath = $user->avatarPath;
        $this->avatar_id = $user->avatar_id;

        parent::__construct($config);
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_PROFILE_SETTINGS] = ['full_name', 'email', 'birthdate', 'phone', 'telegram', 'about', 'avatar_id', 'specialities', 'avatar'];
        $scenarios[self::SCENARIO_SECURITY_SETTINGS] = ['oldPassword', 'password', 'password_repeat'];

        return $scenarios;
    }

    public function attributeLabels()
    {
        return [
            'full_name' => 'Ваше имя',
            'birthdate' => 'День рождения',
            'phone' => 'Номер телефона',
            'avatar' => 'Аватар',
            'specialities' => 'Выбор специализаций'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['full_name', 'email', 'oldPassword', 'password', 'password_repeat'], 'required'],
            [['full_name', 'email', 'password', 'about'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 11],
            [['telegram'], 'string', 'max' => 64],
            [['email'], 'email'],
            [
                'email',
                'unique',
                'targetClass' => Users::class,
                // where(['not', ['column_name' => $value]]) ===
                // where(['<>', 'column_name', $value]) (the same)
                'filter' => ['<>', 'id', $this->_user->id]
            ],
            ['avatar', 'file', 'mimeTypes' => 'image/*', 'extensions' => 'png, jpg'],
            ['specialities', 'exist', 'targetClass' => Categories::class, 'targetAttribute' => 'id', 'allowArray' => true],
            [['password'], 'string', 'min' => 8],
            ['password', 'compare'],
            ['birthdate', 'date', 'format' => 'php:d.m.Y'],
        ];
    }

    public function upload(): bool
    {
        if (
            empty($this->avatar)
            || !$this->validate()
        ) {
            return false;
        }

        $this->avatar_id = (new ImageHelper($this->avatar, true))->fileId;

        return true;
    }

    public function updateProfile(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $user = $this->_user;
            $user->full_name = $this->full_name;
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->telegram = $this->telegram;
            $user->about = $this->about;

            $user->birthdate = \DateTimeImmutable::createFromFormat('d.m.Y', $this->birthdate)->format('Y-m-d H:i:s');

            if ($this->upload()) {
                $user->avatar_id = $this->avatar_id;
            }

            $this->saveUserSpecialities();

            if (!$user->save()) {
                Yii::error($user->getErrors());
                throw new LogicException('Internal error');
            }

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            // если возникла ошибка, откатываем транзакцию
            $transaction->rollBack();
            throw $e; // бросаем полученное исключение
        }
    }

    protected function saveUserSpecialities()
    {
        UserCategories::deleteAll(['=', 'user_id', $this->_user->id]);
        if (!empty($this->specialities)) {
            $categoryRows = ArrayHelper::getColumn($this->specialities, function ($category) {
                return [
                    'category_id' => $category,
                    'user_id' => $this->_user->id
                ];
            });

            Yii::$app->db->createCommand()->batchInsert(UserCategories::tableName(), ['category_id', 'user_id'], $categoryRows)->execute();
        }
    }
}