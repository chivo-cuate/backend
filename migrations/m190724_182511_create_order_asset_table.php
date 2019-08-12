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
            'quantity' => $this->integer()->notNull()->defaultValue(1),
            'finished' => $this->smallInteger()->notNull()->defaultValue(0),
            'waiter_id' => $this->integer()->notNull(),
            'cook_id' => $this->integer(),
        ]);
        $this->addForeignKey('fk_orderasset_order', 'order_asset', 'order_id', 'order', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_orderasset_asset', 'order_asset', 'asset_id', 'asset', 'id', 'restrict', 'cascade');
        $this->addForeignKey('fk_orderasset_waiter', 'order_asset', 'waiter_id', 'auth_user', 'id', 'restrict', 'cascade');
        $this->addForeignKey('fk_orderasset_cook', 'order_asset', 'cook_id', 'auth_user', 'id', 'restrict', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%order_asset}}');
    }

}
