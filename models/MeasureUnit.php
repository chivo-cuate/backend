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
            'id' => 'ID',
            'name' => 'Name',
            'abbr' => 'Abbr',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStocks()
    {
        return $this->hasMany(Stock::className(), ['measure_unit_id' => 'id']);
    }
}
