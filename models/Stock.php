<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock".
 *
 * @property int $id
 * @property int $ingredient_id
 * @property double $quantity
 * @property string $measure_unit
 * @property double $availability
 *
 * @property Ingredient $ingredient
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
            [['ingredient_id', 'quantity', 'measure_unit'], 'required'],
            [['ingredient_id'], 'integer'],
            [['quantity', 'availability'], 'number'],
            [['measure_unit'], 'string', 'max' => 255],
            [['ingredient_id'], 'unique'],
            [['measure_unit'], 'unique'],
            [['ingredient_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ingredient::className(), 'targetAttribute' => ['ingredient_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ingredient_id' => Yii::t('app', 'Ingredient ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'measure_unit' => Yii::t('app', 'Measure Unit'),
            'availability' => Yii::t('app', 'Availability'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIngredient()
    {
        return $this->hasOne(Ingredient::className(), ['id' => 'ingredient_id']);
    }
}
