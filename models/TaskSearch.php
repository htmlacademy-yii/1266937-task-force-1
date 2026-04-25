<?php

namespace app\models;

use app\models\Task;
use yii\data\ActiveDataProvider;

/**
 * Модель для фильтрации списка заданий
 */
class TaskSearch extends Task
{
  public ?string $isRemote = null;
  public ?string $noResponses = null;
  public ?string $interval = null;

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

  /**
   * Возвращает список атрибутов для группы чекбоксов "Дополнительно"
   * @return string[]
   */
  public function getAdditionalFields(): array
  {
    return ['noResponses', 'isRemote'];
  }

  /**
   * Возвращает массив периодов для дропдауна
   * @return array<string, string>
   */
  public static function optsInterval(): array
  {
    return [
      '1 hour' => '1 час',
      '12 hours' => '12 часов',
      '24 hours' => '24 часа',
      '1 week' => '1 неделя',
      '1 month' => '1 месяц',
    ];
  }

  /**
   * Создает экземпляр ActiveDataProvider с учетом условий фильтрации
   * @param array $params Массив параметров из запроса ($_GET)
   * @return ActiveDataProvider
   */
  public function search(array $params): ActiveDataProvider
  {
    $query = Task::find()
      ->where(['tasks.STATUS' => self::STATUS_NEW])
      ->with(['category']);

    $tasksDataProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => ['pageSize' => 5],
      'sort' => [
        'defaultOrder' => ['created_at' => SORT_DESC],
      ]
    ]);

    // загружаем данные формы поиска
    if (!($this->load($params) && $this->validate())) {
      return $tasksDataProvider;
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

    return $tasksDataProvider;
  }
}
