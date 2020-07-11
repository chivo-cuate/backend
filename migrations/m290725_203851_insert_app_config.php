<?php

use yii\db\Migration;

/**
 * Class m190722_203851_apply_fixtures_01
 */
class m290725_203851_insert_app_config extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        //Parámetros globales
        $this->insert('app_config', [
            'app_title' => 'Taquería "El Chivo Cuate"',
            'about' => 'Aplicación para mi taquería',
            'address' => 'Chilpancingo',
            'phone' => '+53 5 123 4567',
            'email_address' => 'admin@chivo-cuate.com',
            'email_password' => 'p!s3r5443j',
            'email_host' => 'mail.google.com',
            'email_port' => '567',
            'email_encryption' => 'tls',
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
