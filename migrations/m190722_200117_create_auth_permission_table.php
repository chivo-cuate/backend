<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth_permission}}`.
 */
class m190722_200117_create_auth_permission_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%auth_permission}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'description' => $this->string(),
            'module_id' => $this->integer()->notNull(),
        ], app\utilities\MigrationHelper::getTableOptions($this->db->driverName));
        
        $this->addForeignKey('fk_authperm_module', 'auth_permission', 'module_id', 'auth_module', 'id', 'cascade', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%auth_permission}}');
    }
}
