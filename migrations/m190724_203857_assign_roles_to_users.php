<?php

use yii\db\Migration;

class m190724_203857_assign_roles_to_users extends Migration {

    public function safeUp() {
        $this->insert('auth_user_role', ['user_id' => 1, 'role_id' => 1]);
        $this->insert('auth_user_role', ['user_id' => 2, 'role_id' => 2]);
        $this->insert('auth_user_role', ['user_id' => 3, 'role_id' => 2]);
        $this->insert('auth_user_role', ['user_id' => 4, 'role_id' => 5]);
        $this->insert('auth_user_role', ['user_id' => 5, 'role_id' => 3]);
        $this->insert('auth_user_role', ['user_id' => 6, 'role_id' => 3]);
        $this->insert('auth_user_role', ['user_id' => 7, 'role_id' => 6]);
        $this->insert('auth_user_role', ['user_id' => 8, 'role_id' => 4]);
        $this->insert('auth_user_role', ['user_id' => 9, 'role_id' => 4]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m190722_203851_apply_fixtures_01 cannot be reverted.\n";

        return false;
    }

}
