<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_asset}}`.
 */
class m190724_182511_create_order_asset_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%order_asset}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'asset_id' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(1),
        ]);
        $this->addForeignKey('fk_orderasset_order', 'order_asset', 'order_id', 'order', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_orderasset_asset', 'order_asset', 'asset_id', 'asset', 'id', 'restrict', 'cascade');
        
        $this->createIndex('idx_orderasset_orderasset', 'order_asset', 'order_id, asset_id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%order_asset}}');
    }

}
