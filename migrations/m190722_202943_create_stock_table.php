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
            'quantity' => $this->double()->notNull()->defaultValue(1),
            'price_in' => $this->double()->notNull()->defaultValue(0),
            'asset_id' => $this->integer()->notNull(),
            'branch_id' => $this->integer()->notNull(),
            'measure_unit_id' => $this->integer()->notNull(),

        ], app\utilities\MigrationHelper::getTableOptions($this->db->driverName));

        $this->addForeignKey('fk_stock_asset', 'stock', 'asset_id', 'asset', 'id', 'restrict', 'cascade');
        $this->addForeignKey('fk_stock_branch', 'stock', 'branch_id', 'branch', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_stock_measure', 'stock', 'measure_unit_id', 'measure_unit', 'id', 'cascade', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%stock}}');
    }
}
