<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_permission".
 *
 * @property int $id
 * @property string $name
 * @property string $route
 * @property string $description
 *
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
            [['name', 'route'], 'required'],
            [['name', 'route', 'description'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['route'], 'unique'],
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
            'route' => Yii::t('app', 'Route'),
            'description' => Yii::t('app', 'Description'),
        ];
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
