<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock".
 *
 * @property int $id
 * @property double $quantity
 * @property int $ingredient_id
 * @property int $measure_unit_id
 * @property int $branch_id
 *
 * @property Branch $branch
 * @property Ingredient $ingredient
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
            [['quantity'], 'number'],
            [['ingredient_id', 'measure_unit_id', 'branch_id'], 'required'],
            [['ingredient_id', 'measure_unit_id', 'branch_id'], 'integer'],
            [['ingredient_id', 'branch_id'], 'unique', 'targetAttribute' => ['ingredient_id', 'branch_id']],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
            [['ingredient_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ingredient::className(), 'targetAttribute' => ['ingredient_id' => 'id']],
            [['measure_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => MeasureUnit::className(), 'targetAttribute' => ['measure_unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quantity' => 'Quantity',
            'ingredient_id' => 'Ingredient ID',
            'measure_unit_id' => 'Measure Unit ID',
            'branch_id' => 'Branch ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIngredient()
    {
        return $this->hasOne(Ingredient::className(), ['id' => 'ingredient_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeasureUnit()
    {
        return $this->hasOne(MeasureUnit::className(), ['id' => 'measure_unit_id']);
    }
}
