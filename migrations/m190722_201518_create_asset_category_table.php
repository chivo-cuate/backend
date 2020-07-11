<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%asset_category}}`.
 */
class m190722_201518_create_asset_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%asset_category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'needs_cooking' => $this->smallInteger()->notNull(),
            'measure_unit_type_id' => $this->integer()->notNull(),
        ], app\utilities\MigrationHelper::getTableOptions($this->db->driverName));

        $this->addForeignKey('fk_assetcateg_measuretype', 'asset_category', 'measure_unit_type_id', 'measure_unit_type', 'id', 'restrict','cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%asset_category}}');
    }
}
