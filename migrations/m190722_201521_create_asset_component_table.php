<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%asset}}`.
 */
class m190722_201521_create_asset_component_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%asset_component}}', [
            'id' => $this->primaryKey(),
            'asset_id' => $this->integer()->notNull(),
            'component_id' => $this->integer()->notNull(),
            'quantity' => $this->double()->notNull()->defaultValue(1),
            'measure_unit_id' => $this->integer()->notNull(),
        ], app\utilities\MigrationHelper::getTableOptions($this->db->driverName));
        
        $this->addForeignKey('fk_assetcomp_asset', 'asset_component', 'asset_id', 'asset', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_assetcomp_comp', 'asset_component', 'component_id', 'asset', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_assetcomp_measureunit', 'asset_component', 'measure_unit_id', 'measure_unit', 'id', 'restrict', 'cascade');
        
        $this->createIndex('idx_assetcomp_assetcomp', 'asset_component', 'asset_id, component_id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%asset_component}}');
    }
}
