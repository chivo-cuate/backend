<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock".
 *
 * @property int $id
 * @property int $branch_id
 * @property int $asset_id
 * @property float $quantity
 * @property int $measure_unit_id
 * @property float $price_in
 *
 * @property Asset $asset
 * @property Branch $branch
 * @property MeasureUnit $measureUnit
 */
class Stock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch_id', 'asset_id', 'measure_unit_id'], 'required'],
            [['branch_id', 'asset_id', 'measure_unit_id'], 'integer'],
            [['quantity', 'price_in'], 'number'],
            [['asset_id', 'branch_id', 'price_in'], 'unique', 'targetAttribute' => ['asset_id', 'branch_id', 'price_in']],
            [['asset_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asset::className(), 'targetAttribute' => ['asset_id' => 'id']],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
            [['measure_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => MeasureUnit::className(), 'targetAttribute' => ['measure_unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'branch_id' => Yii::t('app', 'Branch ID'),
            'asset_id' => Yii::t('app', 'Asset ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'measure_unit_id' => Yii::t('app', 'Measure Unit ID'),
            'price_in' => Yii::t('app', 'Price In'),
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
     * Gets query for [[Branch]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
    }

    /**
     * Gets query for [[MeasureUnit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeasureUnit()
    {
        return $this->hasOne(MeasureUnit::className(), ['id' => 'measure_unit_id']);
    }
}
