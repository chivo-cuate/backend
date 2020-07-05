<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "measure_unit".
 *
 * @property int $id
 * @property string $name
 * @property string $abbr
 * @property int $measure_unit_type_id
 *
 * @property Asset[] $assets
 * @property AssetComponent[] $assetComponents
 * @property MeasureUnitType $measureUnitType
 * @property Stock[] $stocks
 */
class MeasureUnit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'measure_unit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'abbr', 'measure_unit_type_id'], 'required'],
            [['measure_unit_type_id'], 'integer'],
            [['name', 'abbr'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['abbr'], 'unique'],
            [['measure_unit_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MeasureUnitType::className(), 'targetAttribute' => ['measure_unit_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'abbr' => Yii::t('app', 'Abbr'),
            'measure_unit_type_id' => Yii::t('app', 'Measure Unit Type ID'),
        ];
    }

    /**
     * Gets query for [[Assets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssets()
    {
        return $this->hasMany(Asset::className(), ['measure_unit_id' => 'id']);
    }

    /**
     * Gets query for [[AssetComponents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssetComponents()
    {
        return $this->hasMany(AssetComponent::className(), ['measure_unit_id' => 'id']);
    }

    /**
     * Gets query for [[MeasureUnitType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeasureUnitType()
    {
        return $this->hasOne(MeasureUnitType::className(), ['id' => 'measure_unit_type_id']);
    }

    /**
     * Gets query for [[Stocks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStocks()
    {
        return $this->hasMany(Stock::className(), ['measure_unit_id' => 'id']);
    }
}
