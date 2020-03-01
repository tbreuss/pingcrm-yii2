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
    use SoftDeleteTrait;

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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(Contact::class, ['organization_id' => 'id']);
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
     * @param int $id
     * @return array|null
     */
    public static function findById($id)
    {
        $organization = static::find()
            ->select('id, name, email, phone, address, city, region, country, postal_code, deleted_at')
            ->with('contacts')
            ->where('id=:id', ['id' => $id])
            ->asArray()
            ->one();

        if (is_null($organization)) {
            return $organization;
        }

        $organization['contacts'] = array_map(function ($row) {
            return [
                'id' => $row['id'],
                'name' => $row['first_name'] . ' ' . $row['last_name'],
                'city' => $row['city'],
                'phone' => $row['phone']
            ];
        }, $organization['contacts']);

        return $organization;
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
     * @param string $search
     * @param string $trashed
     * @return ActiveDataProvider
     */
    public static function findByParams($search = null, $trashed = null)
    {
        $query = (new Query())
            ->select('id, name, phone, city, deleted_at')
            ->from('organizations');

        if (!empty($search)) {
            $query->andWhere(['like', 'name', $search]);
        }

        if ($trashed === 'with') {
        } elseif ($trashed === 'only') {
            $query->andWhere(['not', ['deleted_at' => null]]);
        } else {
            $query->andWhere(['deleted_at' => null]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }

    /**
     * @return array
     */
    public static function getPairs()
    {
        $pairs = (new Query())
            ->select('id, name')
            ->from('organizations')
            ->orderBy('name')
            ->where(['deleted_at' => null])
            ->all();
        return $pairs;
    }

}
