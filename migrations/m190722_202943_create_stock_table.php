<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%stock}}`.
 */
class m190722_202943_create_stock_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%stock}}', [
            'id' => $this->primaryKey(),
            'ingredient_id' => $this->integer()->notNull()->unique(),
            'quantity' => $this->double()->notNull(),
            'measure_unit' => $this->string()->notNull()->unique(),
            'availability' => $this->double()->notNull()->defaultValue(0),
        ]);

        $this->addForeignKey('fk_stock_ing', 'stock', 'ingredient_id', 'ingredient', 'id', 'cascade', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%stock}}');
    }
}
