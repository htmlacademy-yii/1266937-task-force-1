<?php

namespace app\models;

use app\models\Task;
use yii\data\ActiveDataProvider;

/**
 * Модель для фильтрации списка заданий
 */
class TaskSearch extends Task
{
  public $isRemote;
  public $noResponses;
  public $interval;

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['category_id', 'noResponses', 'isRemote', 'interval'], 'safe'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'category_id' => 'Категории',
      'isRemote' => 'Удалённая работа',
      'noResponses' => 'Без откликов',
      'interval' => 'Период',
    ];
  }

  public function getAdditionalFields()
  {
    return ['noResponses', 'isRemote'];
  }

  public static function optsInterval()
  {
    return [
      '1 hour' => '1 час',
      '12 hours' => '12 часов',
      '24 hours' => '24 часа',
      '1 week' => '1 неделя',
      '1 month' => '1 месяц',
    ];
  }

  public function search($params)
  {
    $query = Task::find()
      ->where(['tasks.STATUS' => self::STATUS_NEW])
      ->with(['category']);

    $dataProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSize' => 5,
      ],
      'sort' => [
        'defaultOrder' => [
          'created_at' => SORT_DESC,
        ],
      ]
    ]);

    // загружаем данные формы поиска
    if (!$this->load($params)) {
      return $dataProvider;
    }

    // изменяем запрос, добавляя в него фильтрацию
    $query->andFilterWhere(['category_id' => $this->category_id]);

    if ($this->isRemote) {
      $query->andWhere(['tasks.city_id' => null]);
    }

    if ($this->noResponses) {
      $query->joinWith('responses')
        ->andWhere(['responses.id' => null]);
    }

    if ($this->interval) {
      $dateTime = new \DateTime();

      $date = $dateTime
        ->modify("- $this->interval")
        ->format('Y-m-d H:i:s');

      $query->andWhere(['>=', 'tasks.created_at', $date]);

    }

    return $dataProvider;
  }
}
