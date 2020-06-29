<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_ingredient".
 *
 * @property int $asset_id
 * @property string $asset_name
 * @property int $component_id
 * @property float $required_quantity
 * @property float|null $stock_quantity
 * @property float|null $price_in
 * @property int $measure_unit_id
 * @property float|null $units_left
 */
class ProductIngredient extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_ingredient';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['asset_id', 'component_id', 'measure_unit_id'], 'integer'],
            [['asset_name', 'component_id', 'measure_unit_id'], 'required'],
            [['required_quantity', 'stock_quantity', 'price_in', 'units_left'], 'number'],
            [['asset_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'asset_id' => Yii::t('app', 'Asset ID'),
            'asset_name' => Yii::t('app', 'Asset Name'),
            'component_id' => Yii::t('app', 'Component ID'),
            'required_quantity' => Yii::t('app', 'Required Quantity'),
            'stock_quantity' => Yii::t('app', 'Stock Quantity'),
            'price_in' => Yii::t('app', 'Price In'),
            'measure_unit_id' => Yii::t('app', 'Measure Unit ID'),
            'units_left' => Yii::t('app', 'Units Left'),
        ];
    }
}
