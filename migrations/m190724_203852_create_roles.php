<?php

use yii\db\Migration;

/**
 * Class m190722_203851_apply_fixtures_01
 */
class m190724_203852_create_roles extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->insert('auth_role', ['name' => 'Administrador del Sistema', 'description' => 'Edita las sucursales y administra el acceso a la aplicación.']);
        $this->insert('auth_role', ['name' => 'Gerente de Sucursal', 'description' => 'Edita los datos de su sucursal.']);
        $this->insert('auth_role', ['name' => 'Mesero OPC', 'description' => 'Toma las órdenes para consumir.']);
        $this->insert('auth_role', ['name' => 'Elaborador OPC', 'description' => 'Elabora las órdenes para consumir.']);
        $this->insert('auth_role', ['name' => 'Mesero OPL', 'description' => 'Toma las órdenes para llevar.']);
        $this->insert('auth_role', ['name' => 'Elaborador OPL', 'description' => 'Elabora las órdenes para llevar.']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m190722_203851_apply_fixtures_01 cannot be reverted.\n";

        return false;
    }

}
