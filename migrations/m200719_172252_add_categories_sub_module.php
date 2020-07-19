<?php

use yii\db\Migration;

/**
 * Class m200719_172252_add_categories_sub_module
 */
class m200719_172252_add_categories_sub_module extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //Submodulo
        $this->insert('auth_module', ['name' => 'Categorías', 'slug' => 'categorias', 'parent_id' => 5, 'icon' => 'mdi-view-list']);

        //Permisos
        $this->insert('auth_permission', ['name' => 'Mostrar', 'slug' => 'listar', 'module_id' => 15]);
        $this->insert('auth_permission', ['name' => 'Crear', 'slug' => 'crear', 'module_id' => 15]);
        $this->insert('auth_permission', ['name' => 'Editar', 'slug' => 'editar', 'module_id' => 15]);
        $this->insert('auth_permission', ['name' => 'Eliminar', 'slug' => 'eliminar', 'module_id' => 15]);

        //Roles
        $this->insert('auth_permission_role', ['perm_id' => 43, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 44, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 45, 'role_id' => 1]);
        $this->insert('auth_permission_role', ['perm_id' => 46, 'role_id' => 1]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200719_172252_add_categories_sub_module cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200719_172252_add_categories_sub_module cannot be reverted.\n";

        return false;
    }
    */
}
