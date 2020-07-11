<?php

use yii\db\Migration;

/**
 * Class m190722_203851_apply_fixtures_01
 */
class m190724_203860_assign_sub_modules_perms extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {

        //Permisos del submódulo "Sucursales"
        $this->insert('auth_permission', ['name' => 'Mostrar', 'slug' => 'listar', 'module_id' => 13]);
        $this->insert('auth_permission', ['name' => 'Crear', 'slug' => 'crear', 'module_id' => 13]);
        $this->insert('auth_permission', ['name' => 'Editar', 'slug' => 'editar', 'module_id' => 13]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'slug' => 'eliminar', 'module_id' => 13]);

        //Permisos del submódulo "Roles"
        $this->insert('auth_permission', ['name' => 'Mostrar', 'slug' => 'listar', 'module_id' => 6]);
        $this->insert('auth_permission', ['name' => 'Crear', 'slug' => 'crear', 'module_id' => 6]);
        $this->insert('auth_permission', ['name' => 'Editar', 'slug' => 'editar', 'module_id' => 6]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'slug' => 'eliminar', 'module_id' => 6]);

        //Permisos del submódulo "Usuarios"
        $this->insert('auth_permission', ['name' => 'Mostrar', 'slug' => 'listar', 'module_id' => 7]);
        $this->insert('auth_permission', ['name' => 'Crear', 'slug' => 'crear', 'module_id' => 7]);
        $this->insert('auth_permission', ['name' => 'Editar', 'slug' => 'editar', 'module_id' => 7]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'slug' => 'eliminar', 'module_id' => 7]);

        //Permisos del submódulo "Ingredientes"
        $this->insert('auth_permission', ['name' => 'Mostrar', 'slug' => 'listar', 'module_id' => 8]);
        $this->insert('auth_permission', ['name' => 'Crear', 'slug' => 'crear', 'module_id' => 8]);
        $this->insert('auth_permission', ['name' => 'Editar', 'slug' => 'editar', 'module_id' => 8]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'slug' => 'eliminar', 'module_id' => 8]);

        //Permisos del submódulo "Productos"
        $this->insert('auth_permission', ['name' => 'Mostrar', 'slug' => 'listar', 'module_id' => 9]);
        $this->insert('auth_permission', ['name' => 'Crear', 'slug' => 'crear', 'module_id' => 9]);
        $this->insert('auth_permission', ['name' => 'Editar', 'slug' => 'editar', 'module_id' => 9]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'slug' => 'eliminar', 'module_id' => 9]);

        //Permisos del submódulo "Almacén"
        $this->insert('auth_permission', ['name' => 'Mostrar', 'slug' => 'listar', 'module_id' => 10]);
        $this->insert('auth_permission', ['name' => 'Crear', 'slug' => 'crear', 'module_id' => 10]);
        $this->insert('auth_permission', ['name' => 'Editar', 'slug' => 'editar', 'module_id' => 10]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'slug' => 'eliminar', 'module_id' => 10]);

        //Permisos del submódulo "Menú diario"
        $this->insert('auth_permission', ['name' => 'Mostrar menú de hoy', 'slug' => 'listar', 'module_id' => 11]);
        $this->insert('auth_permission', ['name' => 'Mostrar menú anterior', 'slug' => 'anterior', 'module_id' => 11]);
        $this->insert('auth_permission', ['name' => 'Crear', 'slug' => 'crear', 'module_id' => 11]);
        $this->insert('auth_permission', ['name' => 'Editar', 'slug' => 'editar', 'module_id' => 11]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'slug' => 'eliminar', 'module_id' => 11]);

        //Permisos del submódulo "Órdenes"
        $this->insert('auth_permission', ['name' => 'Listar', 'slug' => 'listar', 'module_id' => 12]);
        $this->insert('auth_permission', ['name' => 'Crear OPC', 'slug' => 'crear', 'module_id' => 12]);
        $this->insert('auth_permission', ['name' => 'Editar', 'slug' => 'editar', 'module_id' => 12]);
        $this->insert('auth_permission', ['name' => 'Cancelar', 'slug' => 'eliminar', 'module_id' => 12]);
        $this->insert('auth_permission', ['name' => 'Elaborar OPC', 'slug' => 'elaborar', 'module_id' => 12]);
        $this->insert('auth_permission', ['name' => 'Cerrar cuenta', 'slug' => 'cerrar', 'module_id' => 12]);

        //Permisos extra
        $this->insert('auth_permission', ['name' => 'Editar permisos', 'slug' => 'editar-permisos', 'module_id' => 6]); //id 36 - Editar permisos del rol
        $this->insert('auth_permission', ['name' => 'Habilitar elaboradores', 'slug' => 'habilitar-elaboradores', 'module_id' => 11]); //id 37 - Habilitar elaboradores del menú
        $this->insert('auth_permission', ['name' => 'Editar ingredientes', 'slug' => 'editar-ingredientes', 'module_id' => 9]); //id 38 - Editar ingredientes de los productos
        $this->insert('auth_permission', ['name' => 'Ver pendientes', 'slug' => 'ver-pendientes', 'module_id' => 12]); //id 39 - Ver órdenes pendientes
        $this->insert('auth_permission', ['name' => 'Servir productos', 'slug' => 'servir-productos', 'module_id' => 12]); //id 40 - Servir productos
        $this->insert('auth_permission', ['name' => 'Crear OPL', 'slug' => 'crear', 'module_id' => 12]);    //id 41 - Crear ordenes para llevar
        $this->insert('auth_permission', ['name' => 'Elaborar OPL', 'slug' => 'elaborar-opl', 'module_id' => 12]);  //id 42 - Elaborar ordenes para llevar

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m190722_203851_apply_fixtures_01 cannot be reverted.\n";

        return false;
    }

}
