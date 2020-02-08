<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property int|null $account_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $email
 * @property string|null $password
 * @property bool|null $owner
 * @property string|null $photo_path
 * @property string|null $remember_token
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 */
class User extends \yii\db\ActiveRecord
{
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
            [['account_id'], 'integer'],
            [['first_name', 'last_name'], 'required'],
            [['owner'], 'boolean'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['first_name', 'last_name'], 'string', 'max' => 25],
            [['email'], 'string', 'max' => 50],
            [['password', 'remember_token'], 'string', 'max' => 255],
            [['photo_path'], 'string', 'max' => 100],
            [['email'], 'unique'],
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
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'password' => 'Password',
            'owner' => 'Owner',
            'photo_path' => 'Photo Path',
            'remember_token' => 'Remember Token',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
