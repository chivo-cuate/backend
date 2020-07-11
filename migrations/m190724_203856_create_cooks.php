<?php

use yii\db\Migration;

/**
 * Class m190722_203851_apply_fixtures_01
 */
class m190724_203856_create_cooks extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $now = time();

        $this->insert('auth_user', [
            'first_name' => 'María',
            'last_name' => 'Labrador',
            'username' => 'elab1',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'elab1@server.com',
            'status' => 10,
            'sex' => 'F',
            'ine' => strtoupper(Yii::$app->security->generateRandomString(10)),
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $this->insert('auth_user', [
            'first_name' => 'Sofía',
            'last_name' => 'Carrasco',
            'username' => 'elab2',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'elab2@server.com',
            'status' => 10,
            'sex' => 'F',
            'ine' => strtoupper(Yii::$app->security->generateRandomString(10)),
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $this->insert('auth_user', [
            'first_name' => 'José',
            'last_name' => 'Vega',
            'username' => 'elab3',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'elab3@server.com',
            'status' => 10,
            'sex' => 'M',
            'ine' => strtoupper(Yii::$app->security->generateRandomString(10)),
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m190722_203851_apply_fixtures_01 cannot be reverted.\n";

        return false;
    }

}
