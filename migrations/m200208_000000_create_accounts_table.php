<?php

use yii\db\Migration;

/**
 * Class m200208_000000_create_accounts_table
 */
class m200208_000000_create_accounts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('accounts', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'created_at' => $this->dateTime()->null(),
            'updated_at' => $this->dateTime()->null()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }

}
