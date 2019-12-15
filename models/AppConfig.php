<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "app_config".
 *
 * @property int $id
 * @property string $app_title
 * @property string $about
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $email_address
 * @property string|null $email_password
 * @property string|null $email_host
 * @property int|null $email_port
 * @property string|null $email_encryption
 */
class AppConfig extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'app_config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['app_title', 'about'], 'required'],
            [['email_port'], 'integer'],
            [['app_title', 'phone'], 'string', 'max' => 50],
            [['about'], 'string', 'max' => 350],
            [['address'], 'string', 'max' => 250],
            [['email_address', 'email_password', 'email_host'], 'string', 'max' => 255],
            [['email_encryption'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_title' => 'App Title',
            'about' => 'About',
            'address' => 'Address',
            'phone' => 'Phone',
            'email_address' => 'Email Address',
            'email_password' => 'Email Password',
            'email_host' => 'Email Host',
            'email_port' => 'Email Port',
            'email_encryption' => 'Email Encryption',
        ];
    }
}
