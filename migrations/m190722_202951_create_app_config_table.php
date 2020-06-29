<?php

use yii\db\Migration;

class m190722_202951_create_app_config_table extends Migration {

    public function safeUp() {
        $this->createTable('app_config', [
            'id' => $this->primaryKey(),
            'app_title' => $this->string(50)->notNull(),
            'about' => $this->string(350)->notNull(),
            'address' => $this->string(250),
            'phone' => $this->string(50),
            'email_address' => $this->string(),
            'email_password' => $this->string(),
            'email_host' => $this->string(),
            'email_port' => $this->integer(),
            'email_encryption' => $this->string(5),
        ], app\utilities\MigrationHelper::getTableOptions($this->db->driverName));
    }

    public function safeDown() {
        $this->dropTable('app_config');
    }
}
