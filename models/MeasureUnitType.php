<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "measure_unit_type".
 *
 * @property int $id
 * @property string $name
 *
 * @property MeasureUnit[] $measureUnits
 */
class MeasureUnitType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'measure_unit_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
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
        ];
    }

    /**
     * Gets query for [[MeasureUnits]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeasureUnits()
    {
        return $this->hasMany(MeasureUnit::className(), ['measure_unit_type_id' => 'id']);
    }
}
