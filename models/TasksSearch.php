<?php

namespace app\models;

use DateTime;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Taskforce\Main\TaskStatuses;

class TasksSearch extends Tasks
{
    /**
     * @var string $categoryId
     */
    public $categoryId;

    /**
     * @var array
     */
    public $categories;

    /**
     * @var boolean
     */
    public $withoutWorker;

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
            ['withoutWorker', 'boolean'],
            ['withoutWorker', 'default', 'value' => null],
            ['categoryId', 'default', 'value' => null]
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

    public function search($params)
    {
        $query = Tasks::find()
            ->joinWith('city')
            ->joinWith('category')
            ->orderBy('published_at DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (empty($params['categoryId'])) {
            $query->andFilterWhere(['current_status' => TaskStatuses::STATUS_NEW]);
        } else {
            $this->categoryId = $params['categoryId'];

            $query->andFilterWhere(['category_id' => (int)$params['categoryId']]);
        }

        // загружаем данные формы поиска и производим валидацию
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // изменяем запрос добавляя в его фильтрацию
        $query->andFilterWhere(['in', 'category_id', $this->categories]);

        if ($this->hoursPeriod) {
            $query->andFilterWhere(['>=', 'published_at', $this->calculateTimeDiff()]);
        }

        if ($this->withoutWorker) {
            $query->andWhere(['not', ['worker_id' => null]]);
        }

        return $dataProvider;
    }
}