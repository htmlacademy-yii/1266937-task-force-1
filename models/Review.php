<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reviews".
 *
 * @property int $id
 * @property string|null $created_at
 * @property int $task_id
 * @property int $customer_id
 * @property int $contractor_id
 * @property int $rating
 * @property string $text_comment
 *
 * @property User $contractor
 * @property User $customer
 * @property Task $task
 */
class Review extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reviews';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['task_id', 'customer_id', 'contractor_id', 'text_comment'], 'required'],
            [['rating'], 'required', 'message' => 'Выберите количество звезд'],
            [['rating'], 'integer', 'min' => 1, 'max' => 5],
            [['task_id', 'customer_id', 'contractor_id'], 'integer'],
            [['text_comment'], 'string'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['customer_id' => 'id']],
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
            'customer_id' => 'ID заказчика',
            'contractor_id' => 'ID исполнителя',
            'rating' => 'Рейтинг',
            'text_comment' => 'Комментарий',
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
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
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
}
