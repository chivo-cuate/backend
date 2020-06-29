<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth_module}}`.
 */
class m190722_195724_create_auth_module_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%auth_module}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string(25),
            'icon' => $this->string(50),
            'parent_id' => $this->integer(),
            'description' => $this->string(100),
        ], app\utilities\MigrationHelper::getTableOptions($this->db->driverName));

        $this->addForeignKey('fk_authmodule_parent', 'auth_module', 'parent_id', 'auth_module', 'id', 'cascade', 'cascade');
        
        $this->createIndex('idx_authmodule_slugparent', 'auth_module', 'slug, parent_id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%auth_module}}');
    }

}
