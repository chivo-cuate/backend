<?php

use yii\db\Migration;

/**
 * Class m190722_203851_apply_fixtures_01
 */
class m190724_203855_create_waiters extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $now = time();

        $this->insert('auth_user', [
            'first_name' => 'Janet',
            'last_name' => 'Rodríguez',
            'username' => 'mesero1',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'mesero1@server.com',
            'status' => 10,
            'sex' => 'F',
            'ine' => strtoupper(Yii::$app->security->generateRandomString(10)),
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $this->insert('auth_user', [
            'first_name' => 'Pedro',
            'last_name' => 'Romero',
            'username' => 'mesero2',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'mesero2@server.com',
            'status' => 10,
            'sex' => 'M',
            'ine' => strtoupper(Yii::$app->security->generateRandomString(10)),
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $this->insert('auth_user', [
            'first_name' => 'Gabriel',
            'last_name' => 'González',
            'username' => 'mesero3',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'mesero3@server.com',
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
