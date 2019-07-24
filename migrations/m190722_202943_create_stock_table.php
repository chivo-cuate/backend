<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%stock}}`.
 */
class m190722_202943_create_stock_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%stock}}', [
            'id' => $this->primaryKey(),
            'quantity' => $this->double()->notNull(),
            'measure_unit' => $this->string()->notNull()->unique(),
            'quantity' => $this->double()->notNull()->defaultValue(0),
            'ingredient_id' => $this->integer()->notNull()->unique(),
            'branch_id' => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk_stock_branch', 'stock', 'branch_id', 'branch', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_stock_ingredient', 'stock', 'ingredient_id', 'ingredient', 'id', 'cascade', 'cascade');
        
        $this->createIndex('idx_stock_ingredbranch', 'stock', 'ingredient_id, branch_id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%stock}}');
    }
}
