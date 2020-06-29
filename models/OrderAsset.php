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
 * @property int $finished
 * @property int $waiter_id
 * @property int|null $cook_id
 *
 * @property Asset $asset
 * @property AuthUser $cook
 * @property Order $order
 * @property AuthUser $waiter
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
            [['order_id', 'asset_id', 'waiter_id'], 'required'],
            [['order_id', 'asset_id', 'quantity', 'finished', 'waiter_id', 'cook_id'], 'integer'],
            [['asset_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asset::className(), 'targetAttribute' => ['asset_id' => 'id']],
            [['cook_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuthUser::className(), 'targetAttribute' => ['cook_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['waiter_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuthUser::className(), 'targetAttribute' => ['waiter_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'asset_id' => Yii::t('app', 'Asset ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'finished' => Yii::t('app', 'Finished'),
            'waiter_id' => Yii::t('app', 'Waiter ID'),
            'cook_id' => Yii::t('app', 'Cook ID'),
        ];
    }

    /**
     * Gets query for [[Asset]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAsset()
    {
        return $this->hasOne(Asset::className(), ['id' => 'asset_id']);
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
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * Gets query for [[Waiter]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWaiter()
    {
        return $this->hasOne(AuthUser::className(), ['id' => 'waiter_id']);
    }
}
