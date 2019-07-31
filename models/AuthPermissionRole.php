<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_permission_role".
 *
 * @property int $id
 * @property int $perm_id
 * @property int $role_id
 *
 * @property AuthRole $role
 * @property AuthPermission $perm
 */
class AuthPermissionRole extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_permission_role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['perm_id', 'role_id'], 'required'],
            [['perm_id', 'role_id'], 'integer'],
            [['perm_id', 'role_id'], 'unique', 'targetAttribute' => ['perm_id', 'role_id']],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRole::className(), 'targetAttribute' => ['role_id' => 'id']],
            [['perm_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuthPermission::className(), 'targetAttribute' => ['perm_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'perm_id' => 'Perm ID',
            'role_id' => 'Role ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(AuthRole::className(), ['id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerm()
    {
        return $this->hasOne(AuthPermission::className(), ['id' => 'perm_id']);
    }
}
