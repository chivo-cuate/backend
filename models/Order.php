<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $date_time
 * @property int $table_number
 * @property int $status
 * @property int $branch_id
 * @property int $waiter_id
 * @property int $cook_id
 *
 * @property Branch $branch
 * @property AuthUser $cook
 * @property AuthUser $waiter
 * @property OrderAsset[] $orderAssets
 * @property Asset[] $assets
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_time', 'table_number', 'branch_id', 'waiter_id'], 'required'],
            [['date_time', 'table_number', 'status', 'branch_id', 'waiter_id', 'cook_id'], 'integer'],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
            [['cook_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuthUser::className(), 'targetAttribute' => ['cook_id' => 'id']],
            [['waiter_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuthUser::className(), 'targetAttribute' => ['waiter_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_time' => 'Date Time',
            'table_number' => 'Table Number',
            'status' => 'Status',
            'branch_id' => 'Branch ID',
            'waiter_id' => 'Waiter ID',
            'cook_id' => 'Cook ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
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
    public function getWaiter()
    {
        return $this->hasOne(AuthUser::className(), ['id' => 'waiter_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderAssets()
    {
        return $this->hasMany(OrderAsset::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssets()
    {
        return $this->hasMany(Asset::className(), ['id' => 'asset_id'])->viaTable('order_asset', ['order_id' => 'id']);
    }
}
