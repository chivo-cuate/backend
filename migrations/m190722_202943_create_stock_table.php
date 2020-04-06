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
            'branch_id' => $this->integer()->notNull(),
            'asset_id' => $this->integer()->notNull(),
            'quantity' => $this->double()->notNull()->defaultValue(1),
            'measure_unit_id' => $this->integer()->notNull(),
            'price_in' => $this->double()->notNull()->defaultValue(0),
        ], app\utilities\MigrationHelper::getTableOptions($this->db->driverName));
        
        $this->addForeignKey('fk_stock_branch', 'stock', 'branch_id', 'branch', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_stock_asset', 'stock', 'asset_id', 'asset', 'id', 'restrict', 'cascade');
        $this->addForeignKey('fk_stock_measureunit', 'stock', 'measure_unit_id', 'measure_unit', 'id', 'restrict', 'cascade');
        
        $this->createIndex('idx_stock_ingredbranch', 'stock', 'asset_id, branch_id, price_in', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%stock}}');
    }
}
