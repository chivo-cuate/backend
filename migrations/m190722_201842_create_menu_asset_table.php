<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%menu_asset}}`.
 */
class m190722_201842_create_menu_asset_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%menu_asset}}', [
            'id' => $this->primaryKey(),
            'menu_id' => $this->integer()->notNull(),
            'asset_id' => $this->integer()->notNull(),
            'price' => $this->double()->notNull(),
            'grams' => $this->integer(),
        ], app\utilities\MigrationHelper::getTableOptions($this->db->driverName));

        $this->addForeignKey('fk_menuasset_menu', 'menu_asset', 'menu_id', 'menu', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_menuasset_prod', 'menu_asset', 'asset_id', 'asset', 'id', 'restrict', 'cascade');

        $this->createIndex('idx_menuasset_menuprod', 'menu_asset', 'menu_id, asset_id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%menu_asset}}');
    }
}
