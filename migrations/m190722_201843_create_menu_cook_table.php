<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%menu_cook}}`.
 */
class m190722_201843_create_menu_cook_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%menu_cook}}', [
            'id' => $this->primaryKey(),
            'menu_id' => $this->integer()->notNull(),
            'cook_id' => $this->integer()->notNull(),
        ], app\utilities\MigrationHelper::getTableOptions($this->db->driverName));

        $this->addForeignKey('fk_menucook_menu', 'menu_cook', 'menu_id', 'menu', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_menucook_cook', 'menu_cook', 'cook_id', 'auth_user', 'id', 'restrict', 'cascade');

        $this->createIndex('idx_menucook_menucook', 'menu_cook', 'menu_id, cook_id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%menu_cook}}');
    }
}
