<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth_permission_role}}`.
 */
class m190722_200310_create_auth_permission_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%auth_permission_role}}', [
            'id' => $this->primaryKey(),
            'perm_id' => $this->integer()->notNull(),
            'role_id' => $this->integer()->notNull(),
        ], app\utilities\MigrationHelper::getTableOptions($this->db->driverName));

        $this->addForeignKey('fk_permrole_user', 'auth_permission_role', 'perm_id', 'auth_permission', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_permrole_role', 'auth_permission_role', 'role_id', 'auth_role', 'id', 'cascade', 'cascade');

        $this->createIndex('idx_permrole_permrole', 'auth_permission_role', 'perm_id, role_id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%auth_permission_role}}');
    }
}
