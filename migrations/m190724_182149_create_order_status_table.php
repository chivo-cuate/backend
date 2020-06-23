<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_status}}`.
 */
class m190724_182149_create_order_status_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('order_status', [
            'id' => $this->integer()->notNull()->unique(),
            'name' => $this->string()->notNull()->unique(),
            'slug' => $this->string()->notNull()->unique(),
        ], app\utilities\MigrationHelper::getTableOptions($this->db->driverName));
        $this->addPrimaryKey('pk_order_status', 'order_status', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('order_status');
    }

}
