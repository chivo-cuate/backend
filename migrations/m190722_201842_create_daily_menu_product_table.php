<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%daily_menu_product}}`.
 */
class m190722_201842_create_daily_menu_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%daily_menu_product}}', [
            'id' => $this->primaryKey(),
            'menu_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'price' => $this->double()->notNull(),
            'grams' => $this->integer(),
        ]);

        $this->addForeignKey('fk_menuprod_menu', 'daily_menu_product', 'menu_id', 'daily_menu', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_menuprod_prod', 'daily_menu_product', 'product_id', 'product', 'id', 'cascade', 'cascade');

        $this->createIndex('idx_menuprod_menuprod', 'daily_menu_product', 'menu_id, product_id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%daily_menu_product}}');
    }
}
