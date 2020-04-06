<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%asset}}`.
 */
class m190722_201520_create_asset_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%asset}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'status' => $this->boolean()->notNull()->defaultValue(true),
            'asset_type_id' => $this->integer()->notNull(),
            'category_id' => $this->integer(),
            'branch_id' => $this->integer()->notNull(),
        ], app\utilities\MigrationHelper::getTableOptions($this->db->driverName));
        
        $this->addForeignKey('fk_asset_assettype', 'asset', 'asset_type_id', 'asset_type', 'id', 'restrict', 'cascade');
        $this->addForeignKey('fk_asset_assetcat', 'asset', 'category_id', 'asset_category', 'id', 'restrict', 'cascade');
        $this->addForeignKey('fk_asset_branch', 'asset', 'branch_id', 'branch', 'id', 'cascade', 'cascade');
        
        $this->createIndex('idx_asset_typename', 'asset', 'name, asset_type_id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%asset}}');
    }
}
