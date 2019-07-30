<?php

use yii\db\Migration;

class m190722_195152_create_auth_user_table extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%auth_user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'ine' => $this->string()->notNull()->unique(),
            'address' => $this->string(),
            'phone_number' => $this->string(),
            'sex' => $this->string(1),
            'auth_key' => $this->string(32)->notNull()->unique(),
            'verification_token' => $this->string(32)->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string(32)->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
                ], $tableOptions);
    }

    public function down() {
        $this->dropTable('{{%auth_user}}');
    }

}
