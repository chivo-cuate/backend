<?php

use yii\db\Migration;

/**
 * Class m190722_203851_apply_fixtures_01
 */
class m190724_203859_create_sub_modules extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {

        $this->insert('auth_module', ['name' => 'Roles', 'slug' => 'roles', 'parent_id' => 1, 'icon' => 'assignment_ind']);
        $this->insert('auth_module', ['name' => 'Usuarios', 'slug' => 'usuarios', 'parent_id' => 1, 'icon' => 'supervisor_account']);
        $this->insert('auth_module', ['name' => 'Ingredientes', 'slug' => 'ingredientes', 'parent_id' => 5, 'icon' => 'mdi-food-variant']);
        $this->insert('auth_module', ['name' => 'Productos', 'slug' => 'productos', 'parent_id' => 5, 'icon' => 'mdi-food']);
        $this->insert('auth_module', ['name' => 'Almacén', 'slug' => 'almacen', 'parent_id' => 2, 'icon' => 'store']);
        $this->insert('auth_module', ['name' => 'Menú diario', 'slug' => 'menu-diario', 'parent_id' => 2, 'icon' => 'assignment']);
        $this->insert('auth_module', ['name' => 'Órdenes', 'slug' => 'ordenes', 'parent_id' => 3, 'icon' => 'local_dining']);
        $this->insert('auth_module', ['name' => 'Sucursales', 'slug' => 'sucursales', 'parent_id' => 4, 'icon' => 'domain']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m190722_203851_apply_fixtures_01 cannot be reverted.\n";

        return false;
    }

}
