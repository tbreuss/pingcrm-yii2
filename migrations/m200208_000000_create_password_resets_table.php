<?php

use yii\db\Migration;

/**
 * Class m200208_000000_create_password_resets_table
 */
class m200208_000000_create_password_resets_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('password_resets', [
            'email' => $this->string()->notNull(),
            'token' => $this->string()->notNull(),
            'created_at' => $this->dateTime()->null()
        ]);
        $this->createIndex('password_resets_email_index', 'password_resets', 'email');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }
}
