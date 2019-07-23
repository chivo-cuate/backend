<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "daily_menu".
 *
 * @property int $id
 * @property string $date
 *
 * @property DailyMenuProduct[] $dailyMenuProducts
 * @property Product[] $products
 */
class DailyMenu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'daily_menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'required'],
            [['date'], 'safe'],
            [['date'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'date' => Yii::t('app', 'Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDailyMenuProducts()
    {
        return $this->hasMany(DailyMenuProduct::className(), ['menu_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('daily_menu_product', ['menu_id' => 'id']);
    }
}
