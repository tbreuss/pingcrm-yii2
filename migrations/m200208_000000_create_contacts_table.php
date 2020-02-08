<?php

use yii\db\Migration;

/**
 * Class m200208_000000_create_contacts_table
 */
class m200208_000000_create_contacts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('contacts', [
            'id' => $this->primaryKey(),
            'account_id' => $this->integer()->notNull(),
            'organization_id' => $this->integer()->null(),
            'first_name' => $this->string(25)->notNull(),
            'last_name' => $this->string(25)->notNull(),
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
        $this->createIndex('contacts_account_id_index', 'contacts', 'account_id');
        $this->createIndex('contacts_organization_id_index', 'contacts', 'organization_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }

}
