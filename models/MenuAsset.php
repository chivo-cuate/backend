<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menu_asset".
 *
 * @property int $id
 * @property int $menu_id
 * @property int $asset_id
 * @property double $price
 * @property int $grams
 *
 * @property Menu $menu
 * @property Asset $asset
 */
class MenuAsset extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu_asset';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['menu_id', 'asset_id', 'price'], 'required'],
            [['menu_id', 'asset_id', 'grams'], 'integer'],
            [['price'], 'number'],
            [['menu_id', 'asset_id'], 'unique', 'targetAttribute' => ['menu_id', 'asset_id']],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['menu_id' => 'id']],
            [['asset_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asset::className(), 'targetAttribute' => ['asset_id' => 'id']],
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
            'asset_id' => 'Asset ID',
            'price' => 'Price',
            'grams' => 'Grams',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsset()
    {
        return $this->hasOne(Asset::className(), ['id' => 'asset_id']);
    }
}
