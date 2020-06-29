<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menu_cook".
 *
 * @property int $id
 * @property int $menu_id
 * @property int $cook_id
 * @property string|null $session_id
 *
 * @property AuthUser $cook
 * @property Menu $menu
 */
class MenuCook extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu_cook';
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
            [['menu_id', 'cook_id'], 'unique', 'targetAttribute' => ['menu_id', 'cook_id']],
            [['cook_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuthUser::className(), 'targetAttribute' => ['cook_id' => 'id']],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['menu_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'menu_id' => Yii::t('app', 'Menu ID'),
            'cook_id' => Yii::t('app', 'Cook ID'),
            'session_id' => Yii::t('app', 'Session ID'),
        ];
    }

    /**
     * Gets query for [[Cook]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCook()
    {
        return $this->hasOne(AuthUser::className(), ['id' => 'cook_id']);
    }

    /**
     * Gets query for [[Menu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }
}
