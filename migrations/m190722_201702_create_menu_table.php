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
            'date' => $this->date()->notNull()->unique(),
            'branch_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk_menu_branch', 'menu', 'branch_id', 'branch', 'id', 'cascade', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%menu}}');
    }

}
