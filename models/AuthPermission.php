<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_permission".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property int $module_id
 *
 * @property AuthModule $module
 * @property AuthPermissionRole[] $authPermissionRoles
 * @property AuthRole[] $roles
 */
class AuthPermission extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_permission';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'module_id'], 'required'],
            [['module_id'], 'integer'],
            [['name', 'slug', 'description'], 'string', 'max' => 255],
            [['module_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuthModule::className(), 'targetAttribute' => ['module_id' => 'id']],
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
            'slug' => Yii::t('app', 'Route'),
            'description' => Yii::t('app', 'Description'),
            'module_id' => Yii::t('app', 'Module ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModule()
    {
        return $this->hasOne(AuthModule::className(), ['id' => 'module_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthPermissionRoles()
    {
        return $this->hasMany(AuthPermissionRole::className(), ['perm_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(AuthRole::className(), ['id' => 'role_id'])->viaTable('auth_permission_role', ['perm_id' => 'id']);
    }
}
