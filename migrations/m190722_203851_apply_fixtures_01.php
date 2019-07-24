<?php

use yii\db\Migration;

/**
 * Class m190722_203851_apply_fixtures_01
 */
class m190722_203851_apply_fixtures_01 extends Migration {

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
            'username' => 'admin',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'admin@server.com',
            'status' => 10,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        //Gerente
        $this->insert('auth_user', [
            'username' => 'gerente',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'gerente@server.com',
            'status' => 10,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        //Mesero
        $this->insert('auth_user', [
            'username' => 'mesero',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'mesero@server.com',
            'status' => 10,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        //Elaborador
        $this->insert('auth_user', [
            'username' => 'elab',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'elab@server.com',
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
        $this->insert('auth_user_role', ['user_id' => 3, 'role_id' => 3]);
        $this->insert('auth_user_role', ['user_id' => 4, 'role_id' => 4]);

        //Permisos
        $this->insert('auth_permission', ['name' => 'Gestionar almacén', 'route' => 'stock/index']);
        $this->insert('auth_permission', ['name' => 'Crear pedidos', 'route' => 'orders/create']);
        $this->insert('auth_permission', ['name' => 'Actualizar pedidos', 'route' => 'orders/update']);
        $this->insert('auth_permission', ['name' => 'Cancelar pedidos', 'route' => 'orders/cancel']);
        $this->insert('auth_permission', ['name' => 'Cerrar cuenta', 'route' => 'orders/checkout']);
        $this->insert('auth_permission', ['name' => 'Listar pedidos', 'route' => 'orders/index']);
        $this->insert('auth_permission', ['name' => 'Elaborar pedidos', 'route' => 'orders/make']);
        $this->insert('auth_permission', ['name' => 'Gestionar sucursales', 'route' => 'branches/index']);
        $this->insert('auth_permission', ['name' => 'Gestionar roles', 'route' => 'roles/index']);
        $this->insert('auth_permission', ['name' => 'Gestionar usuarios', 'route' => 'users/index']);
        $this->insert('auth_permission', ['name' => 'Gestionar ingredientes', 'route' => 'ingredients/index']);
        $this->insert('auth_permission', ['name' => 'Gestionar productos', 'route' => 'products/index']);
        $this->insert('auth_permission', ['name' => 'Gestionar menú diario', 'route' => 'daily-menu/index']);

        //Permisos del administrador
        $this->insert('auth_permission_role', ['perm_id' => 8, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 9, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 10, 'role_id' => 1]);
        
        //Permisos del gerente
        $this->insert('auth_permission_role', ['perm_id' => 1, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 11, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 12, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 13, 'role_id' => 2]);

        //Permisos del mesero
        $this->insert('auth_permission_role', ['perm_id' => 2, 'role_id' => 3]);
        $this->insert('auth_permission_role', ['perm_id' => 3, 'role_id' => 3]);
        $this->insert('auth_permission_role', ['perm_id' => 4, 'role_id' => 3]);
        $this->insert('auth_permission_role', ['perm_id' => 5, 'role_id' => 3]);
        $this->insert('auth_permission_role', ['perm_id' => 6, 'role_id' => 3]);

        //Permisos del elaborador
        $this->insert('auth_permission_role', ['perm_id' => 6, 'role_id' => 4]);
        $this->insert('auth_permission_role', ['perm_id' => 7, 'role_id' => 4]);

        //Params globales
        $this->insert('app_config', [
            'app_title' => 'Taquería "El Chivo Cuate"',
            'about' => 'Aplicación para mi taquería',
            'address' => 'Chilpancingo',
            'email' => 'app@server.com',
            'phone' => '+53 5 123 4567',
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
