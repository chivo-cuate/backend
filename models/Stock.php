<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock".
 *
 * @property int $id
 * @property double $quantity
 * @property string $measure_unit
 * @property int $ingredient_id
 * @property int $branch_id
 *
 * @property Branch $branch
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
            [['quantity'], 'number'],
            [['measure_unit', 'ingredient_id', 'branch_id'], 'required'],
            [['ingredient_id', 'branch_id'], 'integer'],
            [['measure_unit'], 'string', 'max' => 255],
            [['measure_unit'], 'unique'],
            [['ingredient_id'], 'unique'],
            [['ingredient_id', 'branch_id'], 'unique', 'targetAttribute' => ['ingredient_id', 'branch_id']],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
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
            'quantity' => Yii::t('app', 'Quantity'),
            'measure_unit' => Yii::t('app', 'Measure Unit'),
            'ingredient_id' => Yii::t('app', 'Ingredient ID'),
            'branch_id' => Yii::t('app', 'Branch ID'),
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
}
