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
            'status_id' => $this->integer()->notNull()->defaultValue(0),
            'branch_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk_order_status', 'order', 'status_id', 'order_status', 'id', 'restrict', 'cascade');
        $this->addForeignKey('fk_order_branch', 'order', 'branch_id', 'branch', 'id', 'restrict', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%order}}');
    }

}
