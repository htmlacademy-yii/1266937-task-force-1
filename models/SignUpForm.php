<?php

namespace app\models;

use yii\base\Model;
use app\models\User;
use app\models\City;

class SignUpForm extends Model
{
  public ?string $username = null;
  public ?string $email = null;
  public ?int $city_id = null;
  public ?string $password = null;
  public ?string $password_repeat = null;

  public ?bool $is_contractor = null;

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [
        ['username', 'email', 'password', 'password_repeat', 'city_id'],
        'required',
        'message' => 'Заполните поле',
      ],
      [['email'], 'email', 'message' => 'Введите корректный email'],
      [
        ['email'],
        'unique',
        'targetClass' => User::class,
        'targetAttribute' => 'email',
        'message' => 'Пользователь с таким Email уже зарегистрирован'
      ],
      [
        ['password'],
        'string',
        'min' => 8,
        'tooShort' => 'Пароль должен быть не менее 8 символов'
      ],
      [
        ['password_repeat'],
        'compare',
        'compareAttribute' => 'password',
        'message' => 'Пароли не совпадают'
      ],
      [['city_id'], 'integer'],
      [
        ['city_id'],
        'exist',
        'targetClass' => City::class,
        'targetAttribute' => ['city_id' => 'id'],
        'message' => 'Выберите город из списка',
      ],
      [['is_contractor'], 'boolean'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'username' => 'Ваше имя',
      'email' => 'Email',
      'city_id' => 'Город',
      'password' => 'Пароль',
      'password_repeat' => 'Повтор пароля',
      'is_contractor' => 'я собираюсь откликаться на заказы',
    ];
  }
}