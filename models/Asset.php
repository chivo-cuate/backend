<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asset".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property int $asset_type_id
 * @property int|null $category_id
 * @property int $branch_id
 *
 * @property AssetCategory $category
 * @property AssetType $assetType
 * @property Branch $branch
 * @property AssetComponent[] $assetComponents
 * @property AssetComponent[] $assetComponents0
 * @property Asset[] $components
 * @property Asset[] $assets
 * @property MenuAsset[] $menuAssets
 * @property Menu[] $menus
 * @property OrderAsset[] $orderAssets
 * @property Stock[] $stocks
 */
class Asset extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asset';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'asset_type_id', 'branch_id'], 'required'],
            [['status', 'asset_type_id', 'category_id', 'branch_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name', 'asset_type_id'], 'unique', 'targetAttribute' => ['name', 'asset_type_id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => AssetCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['asset_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AssetType::className(), 'targetAttribute' => ['asset_type_id' => 'id']],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
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
            'status' => Yii::t('app', 'Status'),
            'asset_type_id' => Yii::t('app', 'Asset Type ID'),
            'category_id' => Yii::t('app', 'Category ID'),
            'branch_id' => Yii::t('app', 'Branch ID'),
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(AssetCategory::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[AssetType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssetType()
    {
        return $this->hasOne(AssetType::className(), ['id' => 'asset_type_id']);
    }

    /**
     * Gets query for [[Branch]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
    }

    /**
     * Gets query for [[AssetComponents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssetComponents()
    {
        return $this->hasMany(AssetComponent::className(), ['asset_id' => 'id']);
    }

    /**
     * Gets query for [[AssetComponents0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssetComponents0()
    {
        return $this->hasMany(AssetComponent::className(), ['component_id' => 'id']);
    }

    /**
     * Gets query for [[Components]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComponents()
    {
        return $this->hasMany(Asset::className(), ['id' => 'component_id'])->viaTable('asset_component', ['asset_id' => 'id']);
    }

    /**
     * Gets query for [[Assets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssets()
    {
        return $this->hasMany(Asset::className(), ['id' => 'asset_id'])->viaTable('asset_component', ['component_id' => 'id']);
    }

    /**
     * Gets query for [[MenuAssets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenuAssets()
    {
        return $this->hasMany(MenuAsset::className(), ['asset_id' => 'id']);
    }

    /**
     * Gets query for [[Menus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::className(), ['id' => 'menu_id'])->viaTable('menu_asset', ['asset_id' => 'id']);
    }

    /**
     * Gets query for [[OrderAssets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderAssets()
    {
        return $this->hasMany(OrderAsset::className(), ['asset_id' => 'id']);
    }

    /**
     * Gets query for [[Stocks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStocks()
    {
        return $this->hasMany(Stock::className(), ['asset_id' => 'id']);
    }
}
