<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asset_component".
 *
 * @property int $id
 * @property int $asset_id
 * @property int $component_id
 * @property float $quantity
 * @property int $measure_unit_id
 *
 * @property Asset $asset
 * @property Asset $component
 * @property MeasureUnit $measureUnit
 */
class AssetComponent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asset_component';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['asset_id', 'component_id', 'measure_unit_id'], 'required'],
            [['asset_id', 'component_id', 'measure_unit_id'], 'integer'],
            [['quantity'], 'number'],
            [['asset_id', 'component_id'], 'unique', 'targetAttribute' => ['asset_id', 'component_id']],
            [['asset_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asset::className(), 'targetAttribute' => ['asset_id' => 'id']],
            [['component_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asset::className(), 'targetAttribute' => ['component_id' => 'id']],
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
            'asset_id' => Yii::t('app', 'Asset ID'),
            'component_id' => Yii::t('app', 'Component ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'measure_unit_id' => Yii::t('app', 'Measure Unit ID'),
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
     * Gets query for [[Component]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComponent()
    {
        return $this->hasOne(Asset::className(), ['id' => 'component_id']);
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
