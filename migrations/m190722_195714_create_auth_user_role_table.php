<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth_user_role}}`.
 */
class m190722_195714_create_auth_user_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%auth_user_role}}', [
            'id' => $this->primaryKey(),
            'role_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_userrole_role', 'auth_user_role', 'role_id', 'auth_role', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_userrole_user', 'auth_user_role', 'user_id', 'auth_user', 'id', 'cascade', 'cascade');

        $this->createIndex('idx_userrole_roleuser', 'auth_user_role', 'role_id, user_id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%auth_user_role}}');
    }
}
