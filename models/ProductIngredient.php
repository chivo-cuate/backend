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
            'asset_id' => 'Asset ID',
            'asset_name' => 'Asset Name',
            'component_id' => 'Component ID',
            'required_quantity' => 'Required Quantity',
            'stock_quantity' => 'Stock Quantity',
            'price_in' => 'Price In',
            'measure_unit_id' => 'Measure Unit ID',
            'units_left' => 'Units Left',
        ];
    }
}
