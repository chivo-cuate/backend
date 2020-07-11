<?php

use yii\db\Migration;

/**
 * Class m190722_203851_apply_fixtures_01
 */
class m290725_203850_insert_resource_types extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        //Tipos de recursos
        $this->insert('asset_type', ['name' => 'Ingrediente']);
        $this->insert('asset_type', ['name' => 'Producto']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m190722_203851_apply_fixtures_01 cannot be reverted.\n";

        return false;
    }


}
