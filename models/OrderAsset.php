<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_asset".
 *
 * @property int $id
 * @property int $order_id
 * @property int $asset_id
 * @property int $quantity
 * @property double $price_in
 *
 * @property Asset $asset
 * @property Order $order
 */
class OrderAsset extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_asset';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'asset_id', 'price_in'], 'required'],
            [['order_id', 'asset_id', 'quantity'], 'integer'],
            [['price_in'], 'number'],
            [['order_id', 'asset_id'], 'unique', 'targetAttribute' => ['order_id', 'asset_id']],
            [['asset_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asset::className(), 'targetAttribute' => ['asset_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'asset_id' => 'Asset ID',
            'quantity' => 'Quantity',
            'price_in' => 'Price In',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsset()
    {
        return $this->hasOne(Asset::className(), ['id' => 'asset_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
