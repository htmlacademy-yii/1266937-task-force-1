<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Category;
use app\models\Task;
use app\services\FileService;

class TaskForm extends Model
{
  public $title;
  public $description;
  public $category_id;
  public $location;
  public $latitude;
  public $longitude;
  public $budget;
  public $deadline_at;
  public $city_id;
  public $uploadedFiles;

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['title', 'description', 'category_id'], 'required', 'message' => 'Обязательное поле'],
      [
        'title',
        'match',
        'pattern' => '/(\S\s*){10,}/u',
        'message' => 'Длина текста минимум 10 символов'
      ],
      [
        'description',
        'match',
        'pattern' => '/(\S\s*){30,}/u',
        'message' => 'Длина текста минимум 30 символов'
      ],
      [
        'category_id',
        'exist',
        'targetClass' => Category::class,
        'targetAttribute' => 'id',
        'message' => 'Выберите категорию из списка'
      ],
      [
        ['category_id'],
        'exist',
        'skipOnError' => true,
        'targetClass' => Category::class,
        'targetAttribute' => ['category_id' => 'id']
      ],
      [['latitude', 'longitude', 'city_id'], 'safe'],
      [['location'], 'validateCity', 'skipOnEmpty' => false],
      [['latitude', 'longitude'], 'number'],
      [['latitude'], 'number', 'min' => -90, 'max' => 90],
      [['longitude'], 'number', 'min' => -180, 'max' => 180],
      [
        ['budget'],
        'integer',
        'min' => 1,
        'tooSmall' => 'Введите число больше 0',
        'message' => 'Введите целое число'
      ],
      [
        ['deadline_at'],
        'date',
        'format' => 'php:Y-m-d',
        'min' => date('Y-m-d'),
        'tooSmall' => 'Дата не может быть раньше текущего дня',
        'message' => 'Введите дату в формате ГГГГ-ММ-ДД'
      ],
      [
        ['uploadedFiles'],
        'file',
        'skipOnEmpty' => true,
        'maxFiles' => 0,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'title' => 'Мне нужно',
      'description' => 'Подробности задания',
      'category_id' => 'Категория',
      'location' => 'Локация',
      'budget' => 'Бюджет',
      'deadline_at' => 'Срок исполнения',
      'uploadedFiles' => 'Файлы',
    ];
  }

  /**
   * Валидирует форму и сохраняет данные в таблице заданий
   * 
   * @return Task|null
   */
  public function createTask(): Task|null
  {

    if (!$this->validate()) {
      return null;
    }

    $transaction = Yii::$app->db->beginTransaction();

    try {
      $task = new Task();

      $task->title = $this->title;
      $task->description = $this->description;
      $task->category_id = $this->category_id;
      $task->location = $this->location;
      $task->latitude = $this->latitude;
      $task->longitude = $this->longitude;
      $task->budget = $this->budget;
      $task->deadline_at = $this->deadline_at;

      $task->customer_id = Yii::$app->user->id;

      $task->STATUS = Task::STATUS_NEW;

      if (!empty($this->location)) {
        $task->city_id = Yii::$app->user->identity->city_id;
      }

      if (!$task->save()) {
        Yii::error('Ошибки валидации: ' . json_encode($task->getErrors()));
        throw new \Exception('Не удалось сохранить задание');
      }

      if (!empty($this->uploadedFiles)) {
        foreach ($this->uploadedFiles as $file) {
          $fileRecord = FileService::saveFile($file, 'uploads/tasks');

          if ($fileRecord) {
            $taskFile = new TaskFile();
            $taskFile->task_id = $task->id;
            $taskFile->file_id = $fileRecord->id;
            $taskFile->save();
          }
        }
      }

      $transaction->commit();
      return $task;
    } catch (\Throwable $e) {
      $transaction->rollBack();

      Yii::error("Ошибка при создании задания: " . $e->getMessage());

      return null;
    }
  }

  public function validateCity($attribute)
  {
    if (!empty($this->$attribute) && (empty($this->latitude) || empty($this->longitude))) {
      $this->addError($attribute, 'Выберите адрес из списка');
    }
  }
}