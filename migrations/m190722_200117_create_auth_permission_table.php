<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth_permission}}`.
 */
class m190722_200117_create_auth_permission_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%auth_permission}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'route' => $this->string()->notNull()->unique(),
            'description' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%auth_permission}}');
    }
}
