<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_product}}`.
 */
class m190724_182511_create_order_product_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%order_product}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk_orderproduct_order', 'order_product', 'order_id', 'order', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_orderproduct_product', 'order_product', 'product_id', 'product', 'id', 'cascade', 'cascade');
        
        $this->createIndex('idx_orderproduct_orderproduct', 'order_product', 'order_id, product_id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%order_product}}');
    }

}
