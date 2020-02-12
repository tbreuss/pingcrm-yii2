<?php

namespace app\models;

use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "organizations".
 *
 * @property int $id
 * @property int|null $account_id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $city
 * @property string|null $region
 * @property string|null $country
 * @property string|null $postal_code
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 */
class Organization extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organizations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id'], 'integer'],
            [['name'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['email', 'phone', 'city', 'region'], 'string', 'max' => 50],
            [['email'], 'email'],
            [['address'], 'string', 'max' => 150],
            [['country'], 'string', 'max' => 2],
            [['postal_code'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => 'Account ID',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address',
            'city' => 'City',
            'region' => 'Region',
            'country' => 'Country',
            'postal_code' => 'Postal Code',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    public function getContacts()
    {
        return $this->hasMany(Contact::class, ['organization_id' => 'id']);
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => date('Y-m-d H:i:s')
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'account_id',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'account_id'
                ],
                'value' => function () {
                    return 1;
                }
            ]
        ];
    }

    public static function findById($id)
    {
        return static::find()
            ->select('id, name, email, phone, address, city, region, country, postal_code, deleted_at')
            ->with('contacts')
            ->where('id=:id', ['id' => $id])
            ->asArray()
            ->one();
    }

    public static function createFromArray(array $params = [])
    {
        $organization = new static();
        $organization->attributes = $params;
        return $organization;
    }

    public static function deleteById($id)
    {
        $organization = static::findOne($id);
        $organization->deleted_at = date('Y-m-d H:i:s');
        return $organization->update(false, ['deleted_at']);
    }

    public static function restoreById($id)
    {
        $organization = static::findOne($id);
        $organization->deleted_at = null;
        return $organization->update(false, ['deleted_at']);
    }

}
