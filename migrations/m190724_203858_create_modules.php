<?php

use yii\db\Migration;

/**
 * Class m190722_203851_apply_fixtures_01
 */
class m190724_203858_create_modules extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->insert('auth_module', ['name' => 'Seguridad', 'slug' => 'seguridad', 'icon' => 'security']);
        $this->insert('auth_module', ['name' => 'Mi Sucursal', 'slug' => 'sucursal', 'icon' => 'location_city']);
        $this->insert('auth_module', ['name' => 'Clientes', 'slug' => 'ordenes', 'icon' => 'face']);
        $this->insert('auth_module', ['name' => 'Sucursales', 'slug' => 'sucursales', 'icon' => 'domain']);
        $this->insert('auth_module', ['name' => 'Productos', 'slug' => 'productos', 'icon' => 'mdi-food']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m190722_203851_apply_fixtures_01 cannot be reverted.\n";

        return false;
    }

}
