<?php

namespace app\models;

use Yii;

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
 * @property CustomerReview[] $customerReviews
 * @property CustomerReview[] $customerReviews0
 * @property Response[] $responses
 * @property Task[] $tasks
 * @property Task[] $tasks0
 * @property UserCategory[] $userCategories
 */
class User extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const ROLE_CUSTOMER = 'customer';
    const ROLE_CONTRACTOR = 'contractor';

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
        return $this->hasMany(CustomerReview::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[CustomerReviews0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerReviews0()
    {
        return $this->hasMany(CustomerReview::class, ['contractor_id' => 'id']);
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
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks0()
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
}
