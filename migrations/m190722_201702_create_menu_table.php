<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%menu}}`.
 */
class m190722_201702_create_menu_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%menu}}', [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull(),
            'branch_id' => $this->integer()->notNull(),
        ], app\utilities\MigrationHelper::getTableOptions($this->db->driverName));

        $this->createIndex('idx_menu_branchdate', 'menu', 'branch_id, date', true);

        $this->addForeignKey('fk_menu_branch', 'menu', 'branch_id', 'branch', 'id', 'cascade', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%menu}}');
    }

}
