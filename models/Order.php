<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $date_time
 * @property int|null $table_number
 * @property int $order_number
 * @property int $status_id
 * @property int $menu_id
 * @property int $order_type_id
 *
 * @property Notification[] $notifications
 * @property AuthUser[] $users
 * @property Menu $menu
 * @property OrderStatus $status
 * @property OrderType $orderType
 * @property OrderAsset[] $orderAssets
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
            [['date_time', 'order_number', 'menu_id', 'order_type_id'], 'required'],
            [['date_time', 'table_number', 'order_number', 'status_id', 'menu_id', 'order_type_id'], 'integer'],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['menu_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderStatus::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['order_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderType::className(), 'targetAttribute' => ['order_type_id' => 'id']],
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
            'order_number' => 'Order Number',
            'status_id' => 'Status ID',
            'menu_id' => 'Menu ID',
            'order_type_id' => 'Order Type ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(AuthUser::className(), ['id' => 'user_id'])->viaTable('notification', ['order_id' => 'id']);
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
    public function getStatus()
    {
        return $this->hasOne(OrderStatus::className(), ['id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderType()
    {
        return $this->hasOne(OrderType::className(), ['id' => 'order_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderAssets()
    {
        return $this->hasMany(OrderAsset::className(), ['order_id' => 'id']);
    }
}
