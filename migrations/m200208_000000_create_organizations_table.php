<?php

use yii\db\Migration;

/**
 * Class m200208_000000_create_organizations_table
 */
class m200208_000000_create_organizations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('organizations', [
            'id' => $this->primaryKey(),
            'account_id' => $this->integer()->null(),
            'name' => $this->string(100)->notNull(),
            'email' => $this->string(50)->null(),
            'phone' => $this->string(50)->null(),
            'address' => $this->string(150)->null(),
            'city' => $this->string(50)->null(),
            'region' => $this->string(50)->null(),
            'country' => $this->string(2)->null(),
            'postal_code' => $this->string(25)->null(),
            'created_at' => $this->dateTime()->null(),
            'updated_at' => $this->dateTime()->null(),
            'deleted_at' => $this->dateTime()->null()
        ]);
        $this->createIndex('organizations_account_id_index', 'organizations', 'account_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }
}
