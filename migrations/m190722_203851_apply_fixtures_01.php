<?php

use yii\db\Migration;

/**
 * Class m190722_203851_apply_fixtures_01
 */
class m190722_203851_apply_fixtures_01 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $now = time();
        //Admin
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
        
        //Roles
        $this->insert('auth_role', ['name' => 'Administrador']);
        $this->insert('auth_role', ['name' => 'Mesero']);
        $this->insert('auth_role', ['name' => 'Elaborador']);
        
        //Roles y usuarios
        $this->insert('auth_user_role', ['user_id' => 1, 'role_id' => 1]);
        $this->insert('auth_user_role', ['user_id' => 1, 'role_id' => 2]);
        $this->insert('auth_user_role', ['user_id' => 1, 'role_id' => 3]);
        $this->insert('auth_user_role', ['user_id' => 2, 'role_id' => 2]);
        $this->insert('auth_user_role', ['user_id' => 3, 'role_id' => 3]);

        //Permisos
        $this->insert('auth_permission', ['name' => 'Gestionar almacén', 'route' => 'almacen/inicio']);
        $this->insert('auth_permission', ['name' => 'Crear pedidos', 'route' => 'pedidos/crear']);
        $this->insert('auth_permission', ['name' => 'Actualizar pedidos', 'route' => 'pedidos/actualizar']);
        $this->insert('auth_permission', ['name' => 'Cancelar pedidos', 'route' => 'pedidos/cancelar']);
        $this->insert('auth_permission', ['name' => 'Cerrar cuenta', 'route' => 'pedidos/cerrar-cuenta']);
        $this->insert('auth_permission', ['name' => 'Listar pedidos', 'route' => 'pedidos/listar']);
        $this->insert('auth_permission', ['name' => 'Elaborar pedidos', 'route' => 'pedidos/elaborar']);

        //Permisos del admin
        $this->insert('auth_permission_role', ['perm_id' => 1, 'role_id' => 1]);

        //Permisos del mesero
        $this->insert('auth_permission_role', ['perm_id' => 2, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 3, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 4, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 5, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 6, 'role_id' => 2]);

        //Permisos del elaborador
        $this->insert('auth_permission_role', ['perm_id' => 6, 'role_id' => 3]);
        $this->insert('auth_permission_role', ['perm_id' => 7, 'role_id' => 3]);
        
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
    public function safeDown()
    {
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
