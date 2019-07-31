<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "daily_menu".
 *
 * @property int $id
 * @property string $date
 * @property int $branch_id
 *
 * @property Branch $branch
 * @property DailyMenuProduct[] $dailyMenuProducts
 * @property Product[] $products
 */
class DailyMenu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'daily_menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'branch_id'], 'required'],
            [['date'], 'safe'],
            [['branch_id'], 'integer'],
            [['date'], 'unique'],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
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
    public function getDailyMenuProducts()
    {
        return $this->hasMany(DailyMenuProduct::className(), ['menu_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('daily_menu_product', ['menu_id' => 'id']);
    }
}
