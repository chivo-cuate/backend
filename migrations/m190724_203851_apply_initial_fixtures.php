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
        $this->insert('auth_role', ['name' => 'Administrador']);
        $this->insert('auth_role', ['name' => 'Gerente']);
        $this->insert('auth_role', ['name' => 'Mesero']);
        $this->insert('auth_role', ['name' => 'Elaborador']);

        //Administrador
        $this->insert('auth_user', [
            'first_name' => 'Jon', 'last_name' => 'Snow', 'username' => 'admin',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'admin@server.com',
            'status' => 10,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        //Gerentes
        $this->insert('auth_user', [
            'first_name' => 'Arya', 'last_name' => 'Stark', 'username' => 'gerente1',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'gerente1@server.com',
            'status' => 10,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        $this->insert('auth_user', [
            'first_name' => 'Sansa', 'last_name' => 'Stark', 'username' => 'gerente2',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'gerente2@server.com',
            'status' => 10,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        //Meseros
        $this->insert('auth_user', [
            'first_name' => 'Joffrey', 'last_name' => 'Baratheon', 'username' => 'mesero1',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'mesero1@server.com',
            'status' => 10,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        $this->insert('auth_user', [
            'first_name' => 'Ramsay', 'last_name' => 'Snow', 'username' => 'mesero2',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'mesero2@server.com',
            'status' => 10,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        $this->insert('auth_user', [
            'first_name' => 'Euron', 'last_name' => 'Greyjoy', 'username' => 'mesero3',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'mesero3@server.com',
            'status' => 10,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        //Elaboradores
        $this->insert('auth_user', [
            'first_name' => 'Cersei', 'last_name' => 'Lannister', 'username' => 'elab1',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'elab1@server.com',
            'status' => 10,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        $this->insert('auth_user', [
            'first_name' => 'Jaime', 'last_name' => 'Lannister', 'username' => 'elab2',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'elab2@server.com',
            'status' => 10,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        $this->insert('auth_user', [
            'first_name' => 'Tywin', 'last_name' => 'Lannister', 'username' => 'elab3',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'elab3@server.com',
            'status' => 10,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        //Roles y usuarios
        $this->insert('auth_user_role', ['user_id' => 1, 'role_id' => 1]);
        $this->insert('auth_user_role', ['user_id' => 1, 'role_id' => 2]);
        $this->insert('auth_user_role', ['user_id' => 1, 'role_id' => 3]);
        $this->insert('auth_user_role', ['user_id' => 1, 'role_id' => 4]);
        $this->insert('auth_user_role', ['user_id' => 2, 'role_id' => 2]);
        $this->insert('auth_user_role', ['user_id' => 3, 'role_id' => 2]);
        $this->insert('auth_user_role', ['user_id' => 4, 'role_id' => 3]);
        $this->insert('auth_user_role', ['user_id' => 5, 'role_id' => 3]);
        $this->insert('auth_user_role', ['user_id' => 6, 'role_id' => 3]);
        $this->insert('auth_user_role', ['user_id' => 7, 'role_id' => 4]);
        $this->insert('auth_user_role', ['user_id' => 8, 'role_id' => 4]);
        $this->insert('auth_user_role', ['user_id' => 9, 'role_id' => 4]);
        
        //Modulos
        $this->insert('auth_module', ['name' => 'Seguridad', 'slug' => 'seguridad']);
        $this->insert('auth_module', ['name' => 'Administracion de Sucursal', 'slug' => 'sucursal']);
        $this->insert('auth_module', ['name' => 'Clientes', 'slug' => 'ordenes']);
        $this->insert('auth_module', ['name' => 'Sucursales', 'slug' => 'sucursales', 'parent_id' => 1]);   //4
        $this->insert('auth_module', ['name' => 'Roles', 'slug' => 'roles', 'parent_id' => 1]);        //5
        $this->insert('auth_module', ['name' => 'Usuarios', 'slug' => 'usuarios', 'parent_id' => 1]);     //6
        $this->insert('auth_module', ['name' => 'Ingredientes', 'slug' => 'ingredientes', 'parent_id' => 2]); //7
        $this->insert('auth_module', ['name' => 'Almacén', 'slug' => 'almacen', 'parent_id' => 2]);      //8
        $this->insert('auth_module', ['name' => 'Productos', 'slug' => 'productos', 'parent_id' => 2]);    //9
        $this->insert('auth_module', ['name' => 'Menú diario', 'slug' => 'menu-diario', 'parent_id' => 2]);  //10
        $this->insert('auth_module', ['name' => 'Ordenes', 'slug' => 'ordenes', 'parent_id' => 3]);  //11
        
        //Permisos del submodulo "Sucursales"
        $this->insert('auth_permission', ['name' => 'Mostrar', 'route' => 'listar', 'module_id' => 4]);
        $this->insert('auth_permission', ['name' => 'Crear', 'route' => 'crear', 'module_id' => 4]);
        $this->insert('auth_permission', ['name' => 'Editar', 'route' => 'editar', 'module_id' => 4]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'route' => 'eliminar', 'module_id' => 4]);
        
        //Permisos del submodulo "Roles"
        $this->insert('auth_permission', ['name' => 'Mostrar', 'route' => 'listar', 'module_id' => 5]);
        $this->insert('auth_permission', ['name' => 'Crear', 'route' => 'crear', 'module_id' => 5]);
        $this->insert('auth_permission', ['name' => 'Editar', 'route' => 'editar', 'module_id' => 5]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'route' => 'eliminar', 'module_id' => 5]);
        
        //Permisos del submodulo "Usuarios"
        $this->insert('auth_permission', ['name' => 'Mostrar', 'route' => 'listar', 'module_id' => 6]);
        $this->insert('auth_permission', ['name' => 'Crear', 'route' => 'crear', 'module_id' => 6]);
        $this->insert('auth_permission', ['name' => 'Editar', 'route' => 'editar', 'module_id' => 6]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'route' => 'eliminar', 'module_id' => 6]);
        
        //Permisos del submodulo "Ingredientes"
        $this->insert('auth_permission', ['name' => 'Mostrar', 'route' => 'listar', 'module_id' => 7]);
        $this->insert('auth_permission', ['name' => 'Crear', 'route' => 'crear', 'module_id' => 7]);
        $this->insert('auth_permission', ['name' => 'Editar', 'route' => 'editar', 'module_id' => 7]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'route' => 'eliminar', 'module_id' => 7]);
        
        //Permisos del submodulo "Almacen"
        $this->insert('auth_permission', ['name' => 'Mostrar contenido', 'route' => 'listar', 'module_id' => 8]);
        $this->insert('auth_permission', ['name' => 'Crear', 'route' => 'crear', 'module_id' => 8]);
        $this->insert('auth_permission', ['name' => 'Editar', 'route' => 'editar', 'module_id' => 8]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'route' => 'eliminar', 'module_id' => 8]);
        
        //Permisos del submodulo "Productos"
        $this->insert('auth_permission', ['name' => 'Mostrar', 'route' => 'listar', 'module_id' => 9]);
        $this->insert('auth_permission', ['name' => 'Crear', 'route' => 'crear', 'module_id' => 9]);
        $this->insert('auth_permission', ['name' => 'Editar', 'route' => 'editar', 'module_id' => 9]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'route' => 'eliminar', 'module_id' => 9]);
        
        //Permisos del submodulo "Menu diario"
        $this->insert('auth_permission', ['name' => 'Mostrar menu de hoy', 'route' => 'listar', 'module_id' => 10]);
        $this->insert('auth_permission', ['name' => 'Mostrar menu anterior', 'route' => 'anterior', 'module_id' => 10]);
        $this->insert('auth_permission', ['name' => 'Crear', 'route' => 'crear', 'module_id' => 10]);
        $this->insert('auth_permission', ['name' => 'Editar', 'route' => 'editar', 'module_id' => 10]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'route' => 'eliminar', 'module_id' => 10]);

        //Permisos del submodulo "Ordenes"
        $this->insert('auth_permission', ['name' => 'Listar', 'route' => 'listar', 'module_id' => 11]);
        $this->insert('auth_permission', ['name' => 'Crear', 'route' => 'crear', 'module_id' => 11]);
        $this->insert('auth_permission', ['name' => 'Editar', 'route' => 'editar', 'module_id' => 11]);
        $this->insert('auth_permission', ['name' => 'Cancelar', 'route' => 'eliminar', 'module_id' => 11]);
        $this->insert('auth_permission', ['name' => 'Elaborar', 'route' => 'elaborar', 'module_id' => 11]);
        $this->insert('auth_permission', ['name' => 'Cerrar cuenta', 'route' => 'cerrar', 'module_id' => 11]);
        
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
        
        //Params globales
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
