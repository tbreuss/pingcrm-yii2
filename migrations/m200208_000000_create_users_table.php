<?php

use yii\db\Migration;

/**
 * Class m200208_000000_create_users_table
 */
class m200208_000000_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'account_id' => $this->integer()->null(),
            'first_name' => $this->string(25)->notNull(),
            'last_name' => $this->string(25)->notNull(),
            'email' => $this->string(50)->unique(),
            'password' => $this->string()->null(),
            'owner' => $this->integer(1)->defaultValue(0),
            'photo_path' => $this->string(100)->null(),
            'remember_token' => $this->string()->null(),
            'created_at' => $this->dateTime()->null(),
            'updated_at' => $this->dateTime()->null(),
            'deleted_at' => $this->dateTime()->null()
        ]);
        $this->createIndex('users_account_id_index', 'users', 'account_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }

}
