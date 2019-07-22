<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%daily_menu}}`.
 */
class m190722_201702_create_daily_menu_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%daily_menu}}', [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull()->unique(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%daily_menu}}');
    }
}
