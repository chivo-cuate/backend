<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menu_cook".
 *
 * @property int $id
 * @property int $menu_id
 * @property int $cook_id
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
            'id' => 'ID',
            'menu_id' => 'Menu ID',
            'cook_id' => 'Cook ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCook()
    {
        return $this->hasOne(AuthUser::className(), ['id' => 'cook_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }
}
