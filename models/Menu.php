<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $date
 * @property int $branch_id
 *
 * @property Branch $branch
 * @property MenuAsset[] $menuAssets
 * @property Asset[] $assets
 * @property MenuCook[] $menuCooks
 * @property AuthUser[] $cooks
 * @property Order[] $orders
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'branch_id'], 'required'],
            [['date'], 'safe'],
            [['branch_id'], 'integer'],
            [['date'], 'unique'],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
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
            'branch_id' => Yii::t('app', 'Branch ID'),
        ];
    }

    /**
     * Gets query for [[Branch]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
    }

    /**
     * Gets query for [[MenuAssets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenuAssets()
    {
        return $this->hasMany(MenuAsset::className(), ['menu_id' => 'id']);
    }

    /**
     * Gets query for [[Assets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssets()
    {
        return $this->hasMany(Asset::className(), ['id' => 'asset_id'])->viaTable('menu_asset', ['menu_id' => 'id']);
    }

    /**
     * Gets query for [[MenuCooks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenuCooks()
    {
        return $this->hasMany(MenuCook::className(), ['menu_id' => 'id']);
    }

    /**
     * Gets query for [[Cooks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCooks()
    {
        return $this->hasMany(AuthUser::className(), ['id' => 'cook_id'])->viaTable('menu_cook', ['menu_id' => 'id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['menu_id' => 'id']);
    }
}
