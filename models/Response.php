<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "responses".
 *
 * @property int $id
 * @property string|null $created_at
 * @property int $task_id
 * @property int $contractor_id
 * @property string|null $text_comment
 * @property int|null $price
 * @property string $STATUS
 *
 * @property User $contractor
 * @property Task $task
 */
class Response extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    public const string STATUS_NEW = 'new';
    public const string STATUS_ACCEPTED = 'accepted';
    public const string STATUS_REJECTED = 'rejected';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'responses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text_comment', 'price'], 'default', 'value' => null],
            [['price'], 'integer', 'min' => 1, 'tooSmall' => 'Введите число больше 0', 'message' => 'Введите целое число'],
            [['STATUS'], 'default', 'value' => 'new'],
            [['created_at'], 'safe'],
            [['task_id', 'contractor_id'], 'required'],
            [['task_id', 'contractor_id', 'price'], 'integer'],
            [['text_comment', 'STATUS'], 'string'],
            ['STATUS', 'in', 'range' => array_keys(self::optsSTATUS())],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
            [['contractor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['contractor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Создано',
            'task_id' => 'ID задания',
            'contractor_id' => 'ID заказчика',
            'text_comment' => 'Ваш комментарий',
            'price' => 'Ваша цена',
            'STATUS' => 'Статус',
        ];
    }

    /**
     * Gets query for [[Contractor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContractor()
    {
        return $this->hasOne(User::class, ['id' => 'contractor_id']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }


    /**
     * column STATUS ENUM value labels
     * @return string[]
     */
    public static function optsSTATUS()
    {
        return [
            self::STATUS_NEW => 'new',
            self::STATUS_ACCEPTED => 'accepted',
            self::STATUS_REJECTED => 'rejected',
        ];
    }

    /**
     * @return string
     */
    public function displaySTATUS()
    {
        return self::optsSTATUS()[$this->STATUS];
    }

    /**
     * @return bool
     */
    public function isSTATUSNew()
    {
        return $this->STATUS === self::STATUS_NEW;
    }

    public function setSTATUSToNew()
    {
        $this->STATUS = self::STATUS_NEW;
    }

    /**
     * @return bool
     */
    public function isSTATUSAccepted()
    {
        return $this->STATUS === self::STATUS_ACCEPTED;
    }

    public function setSTATUSToAccepted()
    {
        $this->STATUS = self::STATUS_ACCEPTED;
    }

    /**
     * @return bool
     */
    public function isSTATUSRejected()
    {
        return $this->STATUS === self::STATUS_REJECTED;
    }

    public function setSTATUSToRejected()
    {
        $this->STATUS = self::STATUS_REJECTED;
    }
}
