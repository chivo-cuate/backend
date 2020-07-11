<?php

use yii\db\Migration;

/**
 * Class m190722_203851_apply_fixtures_01
 */
class m190724_203861_assign_perms_roles extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
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
        $this->insert('auth_permission_role', ['perm_id' => 13, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 36, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 17, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 18, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 19, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 20, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 38, 'role_id' => 1]);

        //Permisos del Gerente
        $this->insert('auth_permission_role', ['perm_id' => 14, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 15, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 16, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 21, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 22, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 23, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 24, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 25, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 26, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 27, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 28, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 29, 'role_id' => 2]);
        $this->insert('auth_permission_role', ['perm_id' => 37, 'role_id' => 2]);

        //Permisos del Mesero OPC
        $this->insert('auth_permission_role', ['perm_id' => 30, 'role_id' => 3]);
        $this->insert('auth_permission_role', ['perm_id' => 31, 'role_id' => 3]);
        $this->insert('auth_permission_role', ['perm_id' => 32, 'role_id' => 3]);
        $this->insert('auth_permission_role', ['perm_id' => 33, 'role_id' => 3]);
        $this->insert('auth_permission_role', ['perm_id' => 35, 'role_id' => 3]);
        $this->insert('auth_permission_role', ['perm_id' => 40, 'role_id' => 3]);

        //Permisos del Elaborador OPC
        $this->insert('auth_permission_role', ['perm_id' => 34, 'role_id' => 4]);
        $this->insert('auth_permission_role', ['perm_id' => 39, 'role_id' => 4]);
        //$this->insert('auth_permission_role', ['perm_id' => 43, 'role_id' => 4]);

        //Permisos del Mesero OPL
        $this->insert('auth_permission_role', ['perm_id' => 30, 'role_id' => 5]);
        $this->insert('auth_permission_role', ['perm_id' => 32, 'role_id' => 5]);
        $this->insert('auth_permission_role', ['perm_id' => 33, 'role_id' => 5]);
        $this->insert('auth_permission_role', ['perm_id' => 35, 'role_id' => 5]);
        $this->insert('auth_permission_role', ['perm_id' => 40, 'role_id' => 5]);
        $this->insert('auth_permission_role', ['perm_id' => 41, 'role_id' => 5]);

        //Permisos del Elaborador OPL
        $this->insert('auth_permission_role', ['perm_id' => 34, 'role_id' => 6]);
        $this->insert('auth_permission_role', ['perm_id' => 39, 'role_id' => 6]);
        $this->insert('auth_permission_role', ['perm_id' => 42, 'role_id' => 6]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m190722_203851_apply_fixtures_01 cannot be reverted.\n";

        return false;
    }


}
