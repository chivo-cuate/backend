<?php

use yii\db\Migration;

/**
 * Class m200711_172449_add_online_users_submodule
 */
class m200711_172449_add_online_users_submodule extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('auth_module', ['name' => 'Mis elaboradores', 'slug' => 'usuarios-sucursal', 'parent_id' => 2, 'icon' => 'mdi-account']);

        $this->insert('auth_permission', ['name' => 'Listar', 'slug' => 'listar', 'module_id' => 14]);
        $this->insert('auth_permission', ['name' => 'Desconectar', 'slug' => 'desconectar', 'module_id' => 14]);

        $this->insert('auth_permission_role', ['perm_id' => 43, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 44, 'role_id' => 2]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200711_172449_add_online_users_submodule cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200711_172449_add_online_users_submodule cannot be reverted.\n";

        return false;
    }
    */
}
