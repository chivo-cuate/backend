<?php

use yii\db\Migration;

/**
 * Class m190722_203851_apply_fixtures_01
 */
class m290725_203849_insert_order_statuses extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        //Estados de las órdenes
        $this->insert('order_status', ['id' => 0, 'name' => 'Pendiente', 'slug' => 'PND']);
        $this->insert('order_status', ['id' => 1, 'name' => 'En elaboración', 'slug' => 'ELB']);
        $this->insert('order_status', ['id' => 2, 'name' => 'Lista', 'slug' => 'LST']);
        $this->insert('order_status', ['id' => 3, 'name' => 'Cerrada', 'slug' => 'CERR']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m190722_203851_apply_fixtures_01 cannot be reverted.\n";

        return false;
    }


}
