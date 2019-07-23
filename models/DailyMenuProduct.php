<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "daily_menu_product".
 *
 * @property int $id
 * @property int $menu_id
 * @property int $product_id
 * @property double $price
 * @property int $grams
 *
 * @property DailyMenu $menu
 * @property Product $product
 */
class DailyMenuProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'daily_menu_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['menu_id', 'product_id', 'price'], 'required'],
            [['menu_id', 'product_id', 'grams'], 'integer'],
            [['price'], 'number'],
            [['menu_id', 'product_id'], 'unique', 'targetAttribute' => ['menu_id', 'product_id']],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => DailyMenu::className(), 'targetAttribute' => ['menu_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
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
            'product_id' => Yii::t('app', 'Product ID'),
            'price' => Yii::t('app', 'Price'),
            'grams' => Yii::t('app', 'Grams'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(DailyMenu::className(), ['id' => 'menu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
