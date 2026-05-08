<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SecuritySettingsForm extends Model
{
  public $oldPassword;
  public $newPassword;
  public $newPasswordRepeat;

  public $is_contacts_public;

  private $_user;

  public function rules()
  {
    return [
      ['is_contacts_public', 'boolean'],
      [
        ['oldPassword', 'newPassword'],
        'required',
        'when' => fn() => !empty($this->oldPassword) || !empty($this->newPassword),
        'message' => 'Введите старый пароль',
      ],
      [
        'newPasswordRepeat',
        'required',
        'when' => fn() => !empty($this->oldPassword),
        'message' => 'Повторите новый пароль'
      ],
      ['oldPassword', 'validateOldPassword'],
      [
        'newPassword',
        'string',
        'min' => 8,
        'tooShort' => 'Пароль должен содержать не меньше 8 символов',
        'when' => fn() => !empty($this->oldPassword)
      ],
      [
        'newPasswordRepeat',
        'compare',
        'compareAttribute' => 'newPassword',
        'message' => 'Пароли не совпадают',
        'when' => fn() => !empty($this->oldPassword)
      ],
    ];
  }

  public function attributeLabels()
  {
    return [
      'oldPassword' => 'Старый пароль',
      'newPassword' => 'Новый пароль',
      'newPasswordRepeat' => 'Повтор нового пароля',
      'is_contacts_public' => 'Контакты видны только заказчику',
    ];
  }

  public function loadData($user)
  {
    $this->_user = $user;
    $this->is_contacts_public = $user->is_contacts_public;
  }

  public function save()
  {
    if (!$this->validate()) {
      return false;
    }

    $user = $this->_user;
    $user->is_contacts_public = $this->is_contacts_public;

    if (!empty($this->newPassword) && !empty($this->oldPassword)) {
      $user->password_hash = Yii::$app->security->generatePasswordHash($this->newPassword);
    }

    return $user->save(false);
  }

  public function validateOldPassword($attribute)
  {
    if (!$this->hasErrors($attribute)) {

      $user = $this->_user;

      if (!$user || !$user->validatePassword($this->oldPassword)) {
        $this->addError($attribute, 'Неверный пароль');
      }
    }
  }
}