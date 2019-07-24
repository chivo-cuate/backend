<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth_user_role}}`.
 */
class m190722_201504_create_branch_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%branch_user}}', [
            'id' => $this->primaryKey(),
            'branch_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk_branchuser_branch', 'branch_user', 'branch_id', 'branch', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_branchuser_user', 'branch_user', 'user_id', 'auth_user', 'id', 'cascade', 'cascade');
        $this->createIndex('idx_branchuser_branchuser', 'branch_user', 'branch_id, user_id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%branch_user}}');
    }
}
