<?php

namespace app\models;

use app\components\SoftDeleteTrait;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * This is the model class for table "contacts".
 *
 * @property int $id
 * @property int $account_id
 * @property int|null $organization_id
 * @property string $first_name
 * @property string $last_name
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
class Contact extends ActiveRecord
{
    use SoftDeleteTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contacts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name'], 'required'],
            [['account_id', 'organization_id'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['first_name', 'last_name', 'postal_code'], 'string', 'max' => 25],
            [['email', 'phone', 'city', 'region'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 150],
            [['country'], 'string', 'max' => 2],
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
            'organization_id' => 'Organization ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
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

    /**
     * @return array
     */
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
                    return Yii::$app->user->getIdentity()->account_id;
                }
            ]
        ];
    }

    /**
     * @param array $params
     * @return Organization
     */
    public static function fromArray(array $params = [])
    {
        $organization = new static();
        $organization->attributes = $params;
        return $organization;
    }

    /**
     * @param int $id
     * @return Organization|null
     */
    public static function findById($id)
    {
        return static::find()
            ->select('id, first_name, last_name, organization_id, email, phone, address, city, region, country, postal_code, deleted_at')
            ->where('id=:id', ['id' => $id])
            ->asArray()
            ->one();
    }

    /**
     * @param string $search
     * @param string $trashed
     * @return ActiveDataProvider
     */
    public static function findByParams($search = null, $trashed = null)
    {
        $query = (new Query())
            ->select('contacts.id, contacts.first_name, contacts.last_name, contacts.phone, contacts.city, contacts.deleted_at, organizations.name AS organization_name')
            ->from('contacts')
            ->leftJoin('organizations', 'organizations.id = contacts.organization_id');

        if (!empty($search)) {
            $query->andWhere(['like', 'contacts.first_name', $search]);
            $query->orWhere(['like', 'contacts.last_name', $search]);
        }

        if ($trashed === 'with') {
        } elseif ($trashed === 'only') {
            $query->andWhere(['not', ['contacts.deleted_at' => null]]);
        } else {
            $query->andWhere(['contacts.deleted_at' => null]);
        }

        $query->orderBy('contacts.last_name ASC, contacts.first_name');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }
}
