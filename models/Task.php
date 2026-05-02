<?php

namespace app\models;

use Yii;
use app\services\actions\AbstractAction;
use app\services\actions\AcceptAction;
use app\services\actions\CancelAction;
use app\services\actions\CompleteAction;
use app\services\actions\RefuseTaskAction;
use app\services\actions\RespondAction;
use app\services\actions\RefuseContractorAction;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int|null $budget
 * @property string|null $deadline_at
 * @property string|null $created_at
 * @property int $customer_id
 * @property int|null $contractor_id
 * @property int $category_id
 * @property int|null $city_id
 * @property string|null $location
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $STATUS
 *
 * @property Category $category
 * @property City $city
 * @property User $contractor
 * @property User $customer
 * @property Review[] $Reviews
 * @property File[] $files
 * @property Response[] $responses
 * @property TaskFile[] $taskFiles
 */
class Task extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['budget', 'deadline_at', 'contractor_id', 'city_id', 'location', 'latitude', 'longitude'], 'default', 'value' => null],
            [['STATUS'], 'default', 'value' => 'new'],
            [['title', 'description', 'customer_id', 'category_id'], 'required'],
            [['description', 'STATUS'], 'string'],
            [['budget', 'customer_id', 'contractor_id', 'category_id', 'city_id'], 'integer'],
            [['deadline_at', 'created_at'], 'safe'],
            [['latitude', 'longitude'], 'number'],
            [['title'], 'string', 'max' => 80],
            [['location'], 'string', 'max' => 255],
            ['STATUS', 'in', 'range' => array_keys(self::optsSTATUS())],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['customer_id' => 'id']],
            [['contractor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['contractor_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'budget' => 'Budget',
            'deadline_at' => 'Deadline At',
            'created_at' => 'Created At',
            'customer_id' => 'Customer ID',
            'contractor_id' => 'Contractor ID',
            'category_id' => 'Category ID',
            'city_id' => 'City ID',
            'location' => 'Location',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'STATUS' => 'Status',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
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
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['id' => 'file_id'])->viaTable('task_files', ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[TaskFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskFiles()
    {
        return $this->hasMany(TaskFile::class, ['task_id' => 'id']);
    }


    /**
     * column STATUS ENUM value labels
     * @return string[]
     */
    public static function optsSTATUS()
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCELED => 'Отменено',
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_COMPLETED => 'Выполнено',
            self::STATUS_FAILED => 'Провалено',
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
    public function isSTATUSCanceled()
    {
        return $this->STATUS === self::STATUS_CANCELED;
    }

    public function setSTATUSToCanceled()
    {
        $this->STATUS = self::STATUS_CANCELED;
    }

    /**
     * @return bool
     */
    public function isSTATUSInprogress()
    {
        return $this->STATUS === self::STATUS_IN_PROGRESS;
    }

    public function setSTATUSToInprogress()
    {
        $this->STATUS = self::STATUS_IN_PROGRESS;
    }

    /**
     * @return bool
     */
    public function isSTATUSCompleted()
    {
        return $this->STATUS === self::STATUS_COMPLETED;
    }

    public function setSTATUSToCompleted()
    {
        $this->STATUS = self::STATUS_COMPLETED;
    }

    /**
     * @return bool
     */
    public function isSTATUSFailed()
    {
        return $this->STATUS === self::STATUS_FAILED;
    }

    public function setSTATUSToFailed()
    {
        $this->STATUS = self::STATUS_FAILED;
    }

    /**
     * Возвращает статус, в который перейдет задание после выполнения конкретного действия
     *
     * @param AbstractAction $action Объект класса-действия
     * @return string|null Следующий статус задания или null, если статус не меняется
     */
    public function getNextStatus(AbstractAction $action): ?string
    {
        // Возвращает имя класса объекта
        $actionClass = \get_class($action);

        return match ($actionClass) {
            AcceptAction::class => self::STATUS_IN_PROGRESS,
            CancelAction::class => self::STATUS_CANCELED,
            CompleteAction::class => self::STATUS_COMPLETED,
            RefuseTaskAction::class => self::STATUS_FAILED,
            default => null
        };
    }

    /**
     * Возвращает массив объектов-действий для конкретного статуса
     *
     * @param string $status Статус
     * @return AbstractAction[] Массив объектов возможных действий для текущего статуса
     */
    public function getActionsByStatus(string $status): array
    {
        return match ($status) {
            self::STATUS_NEW => [
                new CancelAction(),
                new RespondAction(),
                new AcceptAction(),
                new RefuseContractorAction(),
            ],
            self::STATUS_IN_PROGRESS => [
                new CompleteAction(),
                new RefuseTaskAction()
            ],
            default => []
        };
    }

    /**
     * Возвращает массив объектов доступных действий для пользователя
     *
     * @param int $userId Id пользователя
     * @return AbstractAction[] Массив объектов доступных действий или пустой массив, если нет действий
     */
    public function getAvailableActions(int $userId, string $type): array
    {
        $actions = $this->getActionsByStatus($this->STATUS);

        // Фильтр объектов в зависимости от роли пользователя
        return array_filter(
            $actions,
            fn($action) => $action->getType() === $type
            && $action->isAllowed($userId, $this->customer_id, $this->contractor_id, $this->STATUS)
        );
    }
}
