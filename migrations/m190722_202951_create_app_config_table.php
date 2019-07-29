<?php

use yii\db\Migration;

class m190722_202951_create_app_config_table extends Migration {

    public function safeUp() {
        $tableOptions = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;
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
        ], $tableOptions);
    }

    public function safeDown() {
        $this->dropTable('app_config');
    }
}
