<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_role".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 *
 * @property AuthPermissionRole[] $authPermissionRoles
 * @property AuthPermission[] $perms
 * @property AuthUserRole[] $authUserRoles
 * @property AuthUser[] $users
 */
class AuthRole extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 32],
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
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthPermissionRoles()
    {
        return $this->hasMany(AuthPermissionRole::className(), ['role_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerms()
    {
        return $this->hasMany(AuthPermission::className(), ['id' => 'perm_id'])->viaTable('auth_permission_role', ['role_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthUserRoles()
    {
        return $this->hasMany(AuthUserRole::className(), ['role_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(AuthUser::className(), ['id' => 'user_id'])->viaTable('auth_user_role', ['role_id' => 'id']);
    }
}
