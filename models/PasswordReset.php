<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "password_resets".
 *
 * @property string $email
 * @property string $token
 * @property string|null $created_at
 */
class PasswordReset extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'password_resets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'token'], 'required'],
            [['created_at'], 'safe'],
            [['email', 'token'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'token' => 'Token',
            'created_at' => 'Created At',
        ];
    }
}
