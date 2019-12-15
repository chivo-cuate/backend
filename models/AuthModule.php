<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_module".
 *
 * @property int $id
 * @property string $name
 * @property string|null $slug
 * @property string|null $icon
 * @property int|null $parent_id
 * @property string|null $description
 *
 * @property AuthModule $parent
 * @property AuthModule[] $authModules
 * @property AuthPermission[] $authPermissions
 */
class AuthModule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_module';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['slug'], 'string', 'max' => 25],
            [['icon'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 100],
            [['slug', 'parent_id'], 'unique', 'targetAttribute' => ['slug', 'parent_id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuthModule::className(), 'targetAttribute' => ['parent_id' => 'id']],
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
            'slug' => 'Slug',
            'icon' => 'Icon',
            'parent_id' => 'Parent ID',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(AuthModule::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthModules()
    {
        return $this->hasMany(AuthModule::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthPermissions()
    {
        return $this->hasMany(AuthPermission::className(), ['module_id' => 'id']);
    }
}
