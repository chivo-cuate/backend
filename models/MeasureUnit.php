<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "measure_unit".
 *
 * @property int $id
 * @property string $name
 * @property string $abbr
 *
 * @property AssetComponent[] $assetComponents
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
            [['name', 'abbr'], 'required'],
            [['name', 'abbr'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['abbr'], 'unique'],
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
        ];
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
     * Gets query for [[Stocks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStocks()
    {
        return $this->hasMany(Stock::className(), ['measure_unit_id' => 'id']);
    }
}
