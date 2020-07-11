<?php

use yii\db\Migration;

/**
 * Class m190722_203851_apply_fixtures_01
 */
class m190724_203854_create_managers extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $now = time();

        $this->insert('auth_user', [
            'first_name' => 'Joanna',
            'last_name' => 'González',
            'username' => 'gerente1',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'gerente1@server.com',
            'status' => 10,
            'sex' => 'F',
            'ine' => strtoupper(Yii::$app->security->generateRandomString(10)),
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $this->insert('auth_user', [
            'first_name' => 'Jenny',
            'last_name' => 'Echemendía',
            'username' => 'gerente2',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'verification_token' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('a'),
            'email' => 'gerente2@server.com',
            'status' => 10,
            'sex' => 'F',
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
