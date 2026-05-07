<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\Category;
use app\services\FileService;

class AccountSettingsForm extends Model
{
  public $avatarFile;
  public $username;
  public $email;
  public $birthday;
  public $phone;
  public $telegram;
  public $profile_info;
  public $categoryIds = [];

  private $_user;

  public function rules()
  {
    return [
      [['username', 'email'], 'required', 'message' => 'Обязательное поле'],
      ['email', 'email', 'message' => 'Введите корректный email'],
      [
        'email',
        'unique',
        'targetClass' => User::class,
        'filter' => function ($query) {
          if ($this->_user) {
            $query->andWhere(['!=', 'id', $this->_user->id]);
          }
        },
        'message' => 'Пользователь с таким Email уже зарегистрирован'
      ],
      ['birthday', 'date', 'format' => 'php:Y-m-d', 'message' => 'Выберите дату в календаре'],
      ['phone', 'match', 'pattern' => '/^\d{11}$/', 'message' => 'Введите номер из 11 символов'],
      ['telegram', 'string', 'max' => 64, 'tooLong' => 'Максимальная длина 64 символа'],
      ['avatarFile', 'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 2],
      ['profile_info', 'string'],
      ['categoryIds', 'each', 'rule' => ['exist', 'targetClass' => Category::class, 'targetAttribute' => 'id']],
    ];
  }

  public function attributeLabels()
  {
    return [
      'avatarFile' => 'Аватар',
      'username' => 'Ваше имя',
      'email' => 'Email',
      'birthday' => 'День рождения',
      'phone' => 'Номер телефона',
      'telegram' => 'Telegram',
      'profile_info' => 'Информация о себе',
      'categoryIds' => 'Выбор специализаций',
    ];
  }

  /**
   * Summary of loadData
   * @param mixed $user
   * @return void
   * 
   * @throws \Throwable
   */
  public function loadData($user)
  {
    $this->_user = $user;

    $this->username = $user->username;
    $this->email = $user->email;
    $this->birthday = $user->birthday ? date('Y-m-d', strtotime($user->birthday)) : '';
    $this->phone = $user->phone;
    $this->telegram = $user->telegram;
    $this->profile_info = $user->profile_info;

    $this->categoryIds = ArrayHelper::getColumn($user->userCategories, 'category_id');
  }

  public function save()
  {
    if (!$this->validate()) {
      return false;
    }

    $transaction = Yii::$app->db->beginTransaction();

    try {
      if ($this->avatarFile) {
        $newAvatarFile = FileService::saveFile($this->avatarFile, 'uploads/avatars');

        if ($newAvatarFile) {
          $oldAvatarId = $this->_user->avatar_id;

          $this->_user->avatar_id = $newAvatarFile->id;

          if ($oldAvatarId) {
            FileService::deleteFile($oldAvatarId);
          }
        }
      }

      $this->_user->username = $this->username;
      $this->_user->email = $this->email;

      $this->_user->birthday = $this->birthday ?: null;

      $this->_user->phone = $this->phone;
      $this->_user->telegram = $this->telegram;
      $this->_user->profile_info = $this->profile_info;

      if (!$this->_user->save(false)) {
        throw new \Exception('Ошибка сохранения пользователя');
      }

      Yii::$app->db->createCommand()
        ->delete('user_categories', ['user_id' => $this->_user->id])
        ->execute();

      if (!empty($this->categoryIds)) {
        $rows = [];

        foreach ($this->categoryIds as $categoryId) {
          $rows[] = [$this->_user->id, $categoryId];
        }

        Yii::$app->db->createCommand()
          ->batchInsert('user_categories', ['user_id', 'category_id'], $rows)
          ->execute();
      }

      $transaction->commit();

      return true;
    } catch (\Throwable $e) {
      $transaction->rollBack();

      Yii::error("Ошибка транзакции при сохранении настроек профиля: " . $e->getMessage());

      return false;
    }
  }
}