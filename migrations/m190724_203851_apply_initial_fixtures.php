<?php

use yii\db\Migration;

/**
 * Class m190722_203851_apply_fixtures_01
 */
class m190724_203851_apply_initial_fixtures extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $now = time();

        //Roles
        $this->insert('auth_role', ['name' => 'Administrador del Sistema', 'description' => 'Edita las sucursales y administra el acceso a la aplicación.']);
        $this->insert('auth_role', ['name' => 'Gerente de Sucursal', 'description' => 'Edita los datos de su sucursal.']);
        $this->insert('auth_role', ['name' => 'Mesero', 'description' => 'Atiende a los clientes y toma sus órdenes.']);
        $this->insert('auth_role', ['name' => 'Elaborador', 'description' => 'Elabora las órdenes de los clientes.']);

        //Administrador
        $this->insert('auth_user', [
            'first_name' => 'Jorge',
            'last_name' => 'Martínez',
            'username' => 'admin',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'admin@server.com',
            'status' => 10,
            'sex' => 'M',
            'ine' => strtoupper(Yii::$app->security->generateRandomString(10)),
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        //Gerentes
        $this->insert('auth_user', [
            'first_name' => 'Joanna',
            'last_name' => 'González',
            'username' => 'gerente1',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'gerente1@server.com',
            'status' => 10,
            'sex' => 'F',
            'ine' => strtoupper(Yii::$app->security->generateRandomString(10)),
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        $this->insert('auth_user', [
            'first_name' => 'Jenny',
            'last_name' => 'Echemendía',
            'username' => 'gerente2',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'gerente2@server.com',
            'status' => 10,
            'sex' => 'F',
            'ine' => strtoupper(Yii::$app->security->generateRandomString(10)),
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        //Meseros
        $this->insert('auth_user', [
            'first_name' => 'Janet',
            'last_name' => 'Rodríguez',
            'username' => 'mesero1',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'mesero1@server.com',
            'status' => 10,
            'sex' => 'F',
            'ine' => strtoupper(Yii::$app->security->generateRandomString(10)),
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        $this->insert('auth_user', [
            'first_name' => 'Pedro',
            'last_name' => 'Romero',
            'username' => 'mesero2',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'mesero2@server.com',
            'status' => 10,
            'sex' => 'M',
            'ine' => strtoupper(Yii::$app->security->generateRandomString(10)),
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        $this->insert('auth_user', [
            'first_name' => 'Gabriel',
            'last_name' => 'González',
            'username' => 'mesero3',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'mesero3@server.com',
            'status' => 10,
            'sex' => 'M',
            'ine' => strtoupper(Yii::$app->security->generateRandomString(10)),
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        //Elaboradores
        $this->insert('auth_user', [
            'first_name' => 'María',
            'last_name' => 'Labrador',
            'username' => 'elab1',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'elab1@server.com',
            'status' => 10,
            'sex' => 'F',
            'ine' => strtoupper(Yii::$app->security->generateRandomString(10)),
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        $this->insert('auth_user', [
            'first_name' => 'Sofía',
            'last_name' => 'Carrasco',
            'username' => 'elab2',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'elab2@server.com',
            'status' => 10,
            'sex' => 'F',
            'ine' => strtoupper(Yii::$app->security->generateRandomString(10)),
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        $this->insert('auth_user', [
            'first_name' => 'José',
            'last_name' => 'Vega',
            'username' => 'elab3',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'elab3@server.com',
            'status' => 10,
            'sex' => 'M',
            'ine' => strtoupper(Yii::$app->security->generateRandomString(10)),
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        //Superusuario - todos los roles
        $this->insert('auth_user', [
            'first_name' => 'Marlon',
            'last_name' => 'Pérez',
            'username' => 'superadmin',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'superadmin@server.com',
            'status' => 10,
            'sex' => 'M',
            'ine' => strtoupper(Yii::$app->security->generateRandomString(10)),
            'created_at' => $now,
            'updated_at' => $now
        ]);

        //Roles y usuarios
        $this->insert('auth_user_role', ['user_id' => 1, 'role_id' => 1]);
        $this->insert('auth_user_role', ['user_id' => 2, 'role_id' => 2]);
        $this->insert('auth_user_role', ['user_id' => 3, 'role_id' => 2]);
        $this->insert('auth_user_role', ['user_id' => 4, 'role_id' => 3]);
        $this->insert('auth_user_role', ['user_id' => 5, 'role_id' => 3]);
        $this->insert('auth_user_role', ['user_id' => 6, 'role_id' => 3]);
        $this->insert('auth_user_role', ['user_id' => 7, 'role_id' => 4]);
        $this->insert('auth_user_role', ['user_id' => 8, 'role_id' => 4]);
        $this->insert('auth_user_role', ['user_id' => 9, 'role_id' => 4]);
        $this->insert('auth_user_role', ['user_id' => 10, 'role_id' => 1]);
        $this->insert('auth_user_role', ['user_id' => 10, 'role_id' => 2]);
        $this->insert('auth_user_role', ['user_id' => 10, 'role_id' => 3]);
        $this->insert('auth_user_role', ['user_id' => 10, 'role_id' => 4]);
        
        //Módulos
        $this->insert('auth_module', ['name' => 'Seguridad', 'slug' => 'seguridad', 'icon' => 'security']);
        $this->insert('auth_module', ['name' => 'Mi Sucursal', 'slug' => 'sucursal', 'icon' => 'location_city']);
        $this->insert('auth_module', ['name' => 'Clientes', 'slug' => 'ordenes', 'icon' => 'face']);
        $this->insert('auth_module', ['name' => 'Sucursales', 'slug' => 'sucursales', 'icon' => 'domain']);
        $this->insert('auth_module', ['name' => 'Roles', 'slug' => 'roles', 'parent_id' => 1, 'icon' => 'assignment_ind']);        //5
        $this->insert('auth_module', ['name' => 'Usuarios', 'slug' => 'usuarios', 'parent_id' => 1, 'icon' => 'supervisor_account']);     //6
        $this->insert('auth_module', ['name' => 'Ingredientes', 'slug' => 'ingredientes', 'parent_id' => 2, 'icon' => 'mdi-food-variant']); //7
        $this->insert('auth_module', ['name' => 'Productos', 'slug' => 'productos', 'parent_id' => 2, 'icon' => 'mdi-food']);    //8
        $this->insert('auth_module', ['name' => 'Almacén', 'slug' => 'almacen', 'parent_id' => 2, 'icon' => 'store']);      //9
        
        $this->insert('auth_module', ['name' => 'Menú diario', 'slug' => 'menu-diario', 'parent_id' => 2, 'icon' => 'assignment']);  //10
        $this->insert('auth_module', ['name' => 'Órdenes', 'slug' => 'ordenes', 'parent_id' => 3, 'icon' => 'local_dining']);  //11
        $this->insert('auth_module', ['name' => 'Sucursales', 'slug' => 'sucursales', 'parent_id' => 4, 'icon' => 'domain']);  //12
        
        //Permisos del submódulo "Sucursales"
        $this->insert('auth_permission', ['name' => 'Mostrar', 'slug' => 'listar', 'module_id' => 12]);
        $this->insert('auth_permission', ['name' => 'Crear', 'slug' => 'crear', 'module_id' => 12]);
        $this->insert('auth_permission', ['name' => 'Editar', 'slug' => 'editar', 'module_id' => 12]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'slug' => 'eliminar', 'module_id' => 12]);

        //Permisos del submódulo "Roles"
        $this->insert('auth_permission', ['name' => 'Mostrar', 'slug' => 'listar', 'module_id' => 5]);
        $this->insert('auth_permission', ['name' => 'Crear', 'slug' => 'crear', 'module_id' => 5]);
        $this->insert('auth_permission', ['name' => 'Editar', 'slug' => 'editar', 'module_id' => 5]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'slug' => 'eliminar', 'module_id' => 5]);
        
        //Permisos del submódulo "Usuarios"
        $this->insert('auth_permission', ['name' => 'Mostrar', 'slug' => 'listar', 'module_id' => 6]);
        $this->insert('auth_permission', ['name' => 'Crear', 'slug' => 'crear', 'module_id' => 6]);
        $this->insert('auth_permission', ['name' => 'Editar', 'slug' => 'editar', 'module_id' => 6]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'slug' => 'eliminar', 'module_id' => 6]);
        
        //Permisos del submódulo "Ingredientes"
        $this->insert('auth_permission', ['name' => 'Mostrar', 'slug' => 'listar', 'module_id' => 7]);
        $this->insert('auth_permission', ['name' => 'Crear', 'slug' => 'crear', 'module_id' => 7]);
        $this->insert('auth_permission', ['name' => 'Editar', 'slug' => 'editar', 'module_id' => 7]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'slug' => 'eliminar', 'module_id' => 7]);
        
        //Permisos del submódulo "Productos"
        $this->insert('auth_permission', ['name' => 'Mostrar', 'slug' => 'listar', 'module_id' => 8]);
        $this->insert('auth_permission', ['name' => 'Crear', 'slug' => 'crear', 'module_id' => 8]);
        $this->insert('auth_permission', ['name' => 'Editar', 'slug' => 'editar', 'module_id' => 8]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'slug' => 'eliminar', 'module_id' => 8]);
        
        //Permisos del submódulo "Almacén"
        $this->insert('auth_permission', ['name' => 'Mostrar', 'slug' => 'listar', 'module_id' => 9]);
        $this->insert('auth_permission', ['name' => 'Crear', 'slug' => 'crear', 'module_id' => 9]);
        $this->insert('auth_permission', ['name' => 'Editar', 'slug' => 'editar', 'module_id' => 9]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'slug' => 'eliminar', 'module_id' => 9]);
        
        //Permisos del submódulo "Menú diario"
        $this->insert('auth_permission', ['name' => 'Mostrar menú de hoy', 'slug' => 'listar', 'module_id' => 10]);
        $this->insert('auth_permission', ['name' => 'Mostrar menú anterior', 'slug' => 'anterior', 'module_id' => 10]);
        $this->insert('auth_permission', ['name' => 'Crear', 'slug' => 'crear', 'module_id' => 10]);
        $this->insert('auth_permission', ['name' => 'Editar', 'slug' => 'editar', 'module_id' => 10]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'slug' => 'eliminar', 'module_id' => 10]);

        //Permisos del submódulo "Órdenes"
        $this->insert('auth_permission', ['name' => 'Listar', 'slug' => 'listar', 'module_id' => 11]);
        $this->insert('auth_permission', ['name' => 'Crear', 'slug' => 'crear', 'module_id' => 11]);
        $this->insert('auth_permission', ['name' => 'Editar', 'slug' => 'editar', 'module_id' => 11]);
        $this->insert('auth_permission', ['name' => 'Cancelar', 'slug' => 'eliminar', 'module_id' => 11]);
        $this->insert('auth_permission', ['name' => 'Elaborar', 'slug' => 'elaborar', 'module_id' => 11]);
        $this->insert('auth_permission', ['name' => 'Cerrar cuenta', 'slug' => 'cerrar', 'module_id' => 11]);
        
        //Permisos extra
        $this->insert('auth_permission', ['name' => 'Editar permisos', 'slug' => 'editar-permisos', 'module_id' => 5]); //id 36 - Editar permisos del rol
        
        //Permisos del Administrador
        $this->insert('auth_permission_role', ['perm_id' => 1, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 2, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 3, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 4, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 5, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 6, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 7, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 8, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 9, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 10, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 11, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 12, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 36, 'role_id' => 1]);
        
        //Permisos del Gerente
        $this->insert('auth_permission_role', ['perm_id' => 13, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 14, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 15, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 16, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 17, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 18, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 19, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 20, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 21, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 22, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 23, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 24, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 25, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 26, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 27, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 28, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 29, 'role_id' => 2]);
        
        //Permisos del Mesero
        $this->insert('auth_permission_role', ['perm_id' => 30, 'role_id' => 3]);
        $this->insert('auth_permission_role', ['perm_id' => 31, 'role_id' => 3]);
        $this->insert('auth_permission_role', ['perm_id' => 32, 'role_id' => 3]);
        $this->insert('auth_permission_role', ['perm_id' => 33, 'role_id' => 3]);
        $this->insert('auth_permission_role', ['perm_id' => 35, 'role_id' => 3]);
        
        //Permisos del Elaborador
        $this->insert('auth_permission_role', ['perm_id' => 30, 'role_id' => 4]);
        $this->insert('auth_permission_role', ['perm_id' => 34, 'role_id' => 4]);
        
        //Sucursales
        $this->insert('branch', ['name' => 'Sucursal 1', 'tables' => 15]);
        $this->insert('branch', ['name' => 'Sucursal 2', 'tables' => 11]);
        
        //Sucursales - usuarios
        $this->insert('branch_user', ['branch_id' => 1, 'user_id' => 2]);
        $this->insert('branch_user', ['branch_id' => 1, 'user_id' => 4]);
        $this->insert('branch_user', ['branch_id' => 1, 'user_id' => 6]);
        $this->insert('branch_user', ['branch_id' => 1, 'user_id' => 7]);
        $this->insert('branch_user', ['branch_id' => 1, 'user_id' => 9]);
        $this->insert('branch_user', ['branch_id' => 2, 'user_id' => 3]);
        $this->insert('branch_user', ['branch_id' => 2, 'user_id' => 5]);
        $this->insert('branch_user', ['branch_id' => 2, 'user_id' => 6]);
        $this->insert('branch_user', ['branch_id' => 2, 'user_id' => 8]);
        $this->insert('branch_user', ['branch_id' => 2, 'user_id' => 9]);
        
        //Unidades de medida
        $this->insert('measure_unit', ['name' => 'Kilogramos', 'abbr' => 'kgs']);
        $this->insert('measure_unit', ['name' => 'Litros', 'abbr' => 'lts']);
        $this->insert('measure_unit', ['name' => 'Unidades', 'abbr' => 'u']);
        
        //Tipos de recursos
        $this->insert('asset_type', ['name' => 'Ingrediente']);
        $this->insert('asset_type', ['name' => 'Producto']);
        
        //Parámetros globales
        $this->insert('app_config', [
            'app_title' => 'Taquería "El Chivo Cuate"',
            'about' => 'Aplicación para mi taquería',
            'address' => 'Chilpancingo',
            'phone' => '+53 5 123 4567',
            'email_address' => 'fdbatista@gmail.com',
            'email_password' => 'Pass123*LTU',
            'email_host' => 'mail.google.com',
            'email_port' => '567',
            'email_encryption' => 'tls',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m190722_203851_apply_fixtures_01 cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190722_203851_apply_fixtures_01 cannot be reverted.\n";

      return false;
      }
     */
}
