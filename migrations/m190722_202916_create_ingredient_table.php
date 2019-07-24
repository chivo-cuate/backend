<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ingredient}}`.
 */
class m190722_202916_create_ingredient_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ingredient}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'branch_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk_ingredient_branch', 'ingredient', 'branch_id', 'branch', 'id', 'cascade', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ingredient}}');
    }
}
