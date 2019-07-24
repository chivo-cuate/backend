<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%daily_menu}}`.
 */
class m190722_201702_create_daily_menu_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%daily_menu}}', [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull()->unique(),
            'branch_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk_dailymenu_branch', 'daily_menu', 'branch_id', 'branch', 'id', 'cascade', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%daily_menu}}');
    }

}
