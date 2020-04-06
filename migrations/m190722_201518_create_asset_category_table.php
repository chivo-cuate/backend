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
        ], app\utilities\MigrationHelper::getTableOptions($this->db->driverName));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%asset_category}}');
    }
}
