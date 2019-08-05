<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order}}`.
 */
class m190724_182150_create_order_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(),
            'date_time' => $this->integer()->notNull(),
            'table_number' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'branch_id' => $this->integer()->notNull(),
            'waiter_id' => $this->integer()->notNull(),
            'cook_id' => $this->integer(),
        ]);
        $this->addForeignKey('fk_order_branch', 'order', 'branch_id', 'branch', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_order_waiter', 'order', 'waiter_id', 'auth_user', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_order_cook', 'order', 'cook_id', 'auth_user', 'id', 'cascade', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%order}}');
    }

}
