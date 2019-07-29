<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth_user_role}}`.
 */
class m190722_201503_create_branch_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%branch}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'tables' => $this->integer()->notNull(),
            'description' => $this->string(128),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%branch}}');
    }
}
