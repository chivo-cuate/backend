<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "app_config".
 *
 * @property int $id
 * @property string $app_title
 * @property string $about
 * @property string $address
 * @property string $phone
 * @property string $email_address
 * @property string $email_password
 * @property string $email_host
 * @property int $email_port
 * @property string $email_encryption
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
            'id' => Yii::t('app', 'ID'),
            'app_title' => Yii::t('app', 'App Title'),
            'about' => Yii::t('app', 'About'),
            'address' => Yii::t('app', 'Address'),
            'phone' => Yii::t('app', 'Phone'),
            'email_address' => Yii::t('app', 'Email Address'),
            'email_password' => Yii::t('app', 'Email Password'),
            'email_host' => Yii::t('app', 'Email Host'),
            'email_port' => Yii::t('app', 'Email Port'),
            'email_encryption' => Yii::t('app', 'Email Encryption'),
        ];
    }
}
