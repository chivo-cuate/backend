<?php

use yii\db\Migration;

/**
 * Class m190722_203851_apply_fixtures_01
 */
class m190724_203862_assign_users_to_branches extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('branch', ['name' => 'Sucursal 1', 'tables' => 10, 'network' => '*']);
        $this->insert('branch', ['name' => 'Sucursal 2', 'tables' => 15, 'network' => '*']);

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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190722_203851_apply_fixtures_01 cannot be reverted.\n";

        return false;
    }


}
