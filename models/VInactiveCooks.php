<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "v_inactive_cooks".
 *
 * @property int $menu_id
 * @property int $cook_id
 * @property string|null $session_id
 * @property string|null $full_name
 */
class VInactiveCooks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v_inactive_cooks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['menu_id', 'cook_id'], 'required'],
            [['menu_id', 'cook_id'], 'integer'],
            [['session_id'], 'string', 'max' => 32],
            [['full_name'], 'string', 'max' => 511],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'menu_id' => Yii::t('app', 'Menu ID'),
            'cook_id' => Yii::t('app', 'Cook ID'),
            'session_id' => Yii::t('app', 'Session ID'),
            'full_name' => Yii::t('app', 'Full Name'),
        ];
    }
}
