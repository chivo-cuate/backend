<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_type}}`.
 */
class m191209_035559_create_order_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(32)->notNull()->unique(),
        ], app\utilities\MigrationHelper::getTableOptions($this->db->driverName));

        $this->insert('order_type', ['id' => 1, 'name' => 'Para consumir']);
        $this->insert('order_type', ['id' => 2, 'name' => 'Para llevar']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%order_type}}');
    }
}
