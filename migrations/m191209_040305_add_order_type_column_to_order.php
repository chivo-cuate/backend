<?php

use yii\db\Migration;

/**
 * Class m191209_040305_add_order_type_column_to_order
 */
class m191209_040305_add_order_type_column_to_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'order_type_id', 'integer not null');
        $this->addForeignKey('fk_order_type', 'order', 'order_type_id', 'order_type', 'id', 'cascade', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'order_type_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191209_040305_add_order_type_column_to_order cannot be reverted.\n";

        return false;
    }
    */
}
