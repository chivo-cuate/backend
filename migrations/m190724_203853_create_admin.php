<?php

use yii\db\Migration;

/**
 * Class m190722_203851_apply_fixtures_01
 */
class m190724_203853_create_admin extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $now = time();

        $this->insert('auth_user', [
            'first_name' => 'Jorge',
            'last_name' => 'Martínez',
            'username' => 'admin',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'admin@server.com',
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
    public function safeDown()
    {
        echo "m190722_203851_apply_fixtures_01 cannot be reverted.\n";

        return false;
    }

}
