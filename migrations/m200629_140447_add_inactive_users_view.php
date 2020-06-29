<?php

use yii\db\Migration;

/**
 * Class m200629_140447_add_inactive_users_view
 */
class m200629_140447_add_inactive_users_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            create or replace view v_inactive_cooks as
                select
                    menu_id, cook_id, session_id, concat(first_name, ' ', last_name) full_name
                    from menu_cook mc
                    inner join auth_user au on (mc.cook_id = au.id)
                    where session_id is not null and (UNIX_TIMESTAMP() - activity_at) > 5;
            ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200629_140447_add_inactive_users_view cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200629_140447_add_inactive_users_view cannot be reverted.\n";

        return false;
    }
    */
}
