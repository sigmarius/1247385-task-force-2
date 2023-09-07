<?php

namespace app\models;

use DateTime;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Taskforce\Service\Task\TaskStatuses;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class TasksSearch extends Tasks
{
    const PAGE_SIZE = 5;
    /**
     * @var array
     */
    public $categories;

    /**
     * @var boolean
     */
    public $remoteWork;

    /**
     * @var boolean
     */
    public $withoutReactions;

    /**
     * @var integer
     */
    public $hoursPeriod;

    public function rules()
    {
        // только поля определенные в rules() будут доступны для поиска
        return [
            ['categories', 'filter', 'filter' => function ($categories) {
                return !empty($categories) ? array_map(fn ($category) => (int) $category, $categories) : '';
            }],
            ['hoursPeriod', 'filter', 'filter' => function ($period) {
                return (int) $period;
            }],
            [['remoteWork', 'withoutReactions'], 'boolean'],
            [['remoteWork', 'withoutReactions'], 'default', 'value' => null],
        ];
    }

    /**
     * Отключает правила валидации родительской модели
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function calculateTimeDiff()
    {
        $now = new DateTime();
        $now->modify('-' . $this->hoursPeriod . 'hours');

        return $now->format( 'Y-m-d H:i:s');
    }

    public function search($params, $id)
    {
        $currentUserId = \Yii::$app->user->identity->id;
        $currentUser = Users::findIdentity($currentUserId);

        $query = Tasks::find()
            ->where(['current_status' => TaskStatuses::STATUS_NEW])
            ->andWhere(['is', 'location', null])
            ->orWhere(['in', 'city_id', $currentUser->city_id])
            ->orderBy('published_at DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => self::PAGE_SIZE,
            ],
        ]);

        if (!empty($id)) {
            $this->categories[] = (int)$id;
            $query->andFilterWhere(['in', 'category_id', $this->categories]);
        }

        // загружаем данные формы поиска и производим валидацию
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // если данные формы загрузились, изменяем запрос добавляя в его фильтрацию
        if (!empty($id) && empty($this->categories)) {
            $categories = Categories::find()->asArray()->all();
            $this->categories = ArrayHelper::getColumn($categories, 'id');
        }

        // добавляем полученные из формы категории к запросу
        $query->orFilterWhere(['in', 'category_id', $this->categories]);

        if ($this->hoursPeriod) {
            $query->andFilterWhere(['>=', 'published_at', $this->calculateTimeDiff()]);
        }

        if ($this->remoteWork) {
            $query->andWhere(['is', 'location', null]);
            // сбросили фильтр по id города
            $query->orFilterWhere(['in', 'city_id', null]);
        }

        if ($this->withoutReactions) {
            $subQuery = (new Query())
                ->select('id')
                ->from('reactions')
                ->where('tasks.id = reactions.id');
            $query->andWhere(['not exists', $subQuery]);
        }

        return $dataProvider;
    }
}