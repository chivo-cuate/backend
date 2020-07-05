<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asset_category".
 *
 * @property int $id
 * @property string $name
 * @property int $measure_unit_type_id
 *
 * @property Asset[] $assets
 * @property MeasureUnitType $measureUnitType
 */
class AssetCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asset_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'measure_unit_type_id'], 'required'],
            [['measure_unit_type_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
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
        return $this->hasMany(Asset::className(), ['category_id' => 'id']);
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
}
