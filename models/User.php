<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string|null $created_at
 * @property string $email
 * @property string $username
 * @property string $password_hash
 * @property string $role
 * @property int|null $avatar_id
 * @property int $city_id
 * @property string|null $birthday
 * @property string|null $phone
 * @property string|null $telegram
 * @property string|null $profile_info
 *
 * @property File $avatar
 * @property Category[] $categories
 * @property City $city
 * @property Review[] $customerReviews
 * @property Review[] $contractorReviews
 * @property Response[] $responses
 * @property Task[] $customerTasks
 * @property Task[] $contractorTasks
 * @property UserCategory[] $userCategories
 * 
 * @property-read int|null $age
 * @property-read int $completedTasksCount
 * @property-read int $failedTasksCount
 */
class User extends ActiveRecord implements IdentityInterface
{

    /**
     * ENUM field values
     */
    public const string ROLE_CUSTOMER = 'customer';
    public const string ROLE_CONTRACTOR = 'contractor';

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {

    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {

    }

    public function validateAuthKey($authKey)
    {

    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['avatar_id', 'birthday', 'phone', 'telegram', 'profile_info'], 'default', 'value' => null],
            [['created_at', 'birthday'], 'safe'],
            [['email', 'username', 'password_hash', 'role', 'city_id'], 'required'],
            [['role', 'profile_info'], 'string'],
            [['avatar_id', 'city_id'], 'integer'],
            [['email', 'password_hash'], 'string', 'max' => 255],
            [['username'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 11],
            [['telegram'], 'string', 'max' => 64],
            ['role', 'in', 'range' => array_keys(self::optsRole())],
            [['email'], 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            [['avatar_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::class, 'targetAttribute' => ['avatar_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'email' => 'Email',
            'username' => 'Username',
            'password_hash' => 'Password Hash',
            'role' => 'Role',
            'avatar_id' => 'Avatar ID',
            'city_id' => 'City ID',
            'birthday' => 'Birthday',
            'phone' => 'Phone',
            'telegram' => 'Telegram',
            'profile_info' => 'Profile Info',
        ];
    }

    /**
     * Gets query for [[Avatar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvatar()
    {
        return $this->hasOne(File::class, ['id' => 'avatar_id']);
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->viaTable('user_categories', ['user_id' => 'id']);
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
     * Gets query for [[CustomerReviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerReviews()
    {
        return $this->hasMany(Review::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[ReceivedReviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReceivedReviews()
    {
        return $this->hasMany(Review::class, ['contractor_id' => 'id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['contractor_id' => 'id']);
    }

    /**
     * Gets query for [[CustomerTasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerTasks()
    {
        return $this->hasMany(Task::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[ContractorTasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContractorTasks()
    {
        return $this->hasMany(Task::class, ['contractor_id' => 'id']);
    }

    /**
     * Gets query for [[UserCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserCategories()
    {
        return $this->hasMany(UserCategory::class, ['user_id' => 'id']);
    }


    /**
     * column role ENUM value labels
     * @return string[]
     */
    public static function optsRole()
    {
        return [
            self::ROLE_CUSTOMER => 'customer',
            self::ROLE_CONTRACTOR => 'contractor',
        ];
    }

    /**
     * @return string
     */
    public function displayRole()
    {
        return self::optsRole()[$this->role];
    }

    /**
     * @return bool
     */
    public function isRoleCustomer()
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    public function setRoleToCustomer()
    {
        $this->role = self::ROLE_CUSTOMER;
    }

    /**
     * @return bool
     */
    public function isRoleContractor()
    {
        return $this->role === self::ROLE_CONTRACTOR;
    }

    public function setRoleToContractor()
    {
        $this->role = self::ROLE_CONTRACTOR;
    }

    /**
     * Возвращает количество выполненных заданий
     * @return int
     */
    public function getCompletedTasksCount(): int
    {
        return $this->getContractorTasks()
            ->andWhere(['status' => Task::STATUS_COMPLETED])
            ->count();
    }


    /**
     * Возвращает количество проваленных заданий пользователя
     *
     * @return int
     */
    public function getFailedTasksCount(): int
    {
        return $this->getContractorTasks()
            ->andWhere(['STATUS' => Task::STATUS_FAILED])
            ->count();
    }

    /**
     * Возвращает возраст пользователя в годах в зависимости от даты рождения
     * @return int|null
     */
    public function getAge(): ?int
    {
        if (empty($this->birthday)) {
            return null;
        }

        $birthday = new \DateTime($this->birthday);
        $date = new \DateTime();
        $interval = $birthday->diff($date);

        return $interval->y;
    }

    /**
     * Проверяет, занят ли сейчас пользователь на активном задании
     * @return bool
     */
    public function hasActiveTask(): bool
    {
        return $this->getContractorTasks()
            ->andWhere(['status' => Task::STATUS_IN_PROGRESS])
            ->exists();
    }
}
