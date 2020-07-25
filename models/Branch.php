<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "branch".
 *
 * @property int $id
 * @property string $name
 * @property int $tables
 * @property string $network
 * @property string|null $description
 *
 * @property BranchUser[] $branchUsers
 * @property AuthUser[] $users
 * @property Menu[] $menus
 * @property Stock[] $stocks
 */
class Branch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'branch';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'tables', 'network'], 'required'],
            [['tables'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['network'], 'string', 'max' => 2048],
            [['description'], 'string', 'max' => 128],
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
            'tables' => Yii::t('app', 'Tables'),
            'network' => Yii::t('app', 'Network'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * Gets query for [[BranchUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBranchUsers()
    {
        return $this->hasMany(BranchUser::className(), ['branch_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(AuthUser::className(), ['id' => 'user_id'])->viaTable('branch_user', ['branch_id' => 'id']);
    }

    /**
     * Gets query for [[Menus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::className(), ['branch_id' => 'id']);
    }

    /**
     * Gets query for [[Stocks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStocks()
    {
        return $this->hasMany(Stock::className(), ['branch_id' => 'id']);
    }
}
