<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_asset}}`.
 */
class m190724_182512_create_notification_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'order_id' => $this->integer(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'title' => $this->string()->notNull(),
            'subtitle' => $this->string()->notNull(),
            'headline' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], app\utilities\MigrationHelper::getTableOptions($this->db->driverName));

        $this->addForeignKey('fk_notification_user', 'notification', 'user_id', 'auth_user', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_notification_order', 'notification', 'order_id', 'order', 'id', 'cascade', 'cascade');
        
        //$this->createIndex('idx_notification_orderuser', 'notification', 'user_id, order_id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%order_asset}}');
    }

}
