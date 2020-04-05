<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "auth_user".
 *
 * @property int $id
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property string $ine
 * @property string|null $address
 * @property string|null $phone_number
 * @property string|null $sex
 * @property string $auth_key
 * @property string $verification_token
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AuthUserRole[] $authUserRoles
 * @property AuthRole[] $roles
 * @property BranchUser[] $branchUsers
 * @property Branch[] $branches
 * @property MenuCook[] $menuCooks
 * @property Menu[] $menus
 * @property Notification[] $notifications
 * @property OrderAsset[] $orderAssets
 * @property OrderAsset[] $orderAssets0
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'first_name', 'last_name', 'ine', 'auth_key', 'verification_token', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'first_name', 'last_name', 'ine', 'address', 'phone_number', 'password_hash', 'email'], 'string', 'max' => 255],
            [['sex'], 'string', 'max' => 1],
            [['auth_key', 'verification_token', 'password_reset_token'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['ine'], 'unique'],
            [['auth_key'], 'unique'],
            [['verification_token'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'ine' => 'Ine',
            'address' => 'Address',
            'phone_number' => 'Phone Number',
            'sex' => 'Sex',
            'auth_key' => 'Auth Key',
            'verification_token' => 'Verification Token',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthUserRoles()
    {
        return $this->hasMany(AuthUserRole::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(AuthRole::className(), ['id' => 'role_id'])->viaTable('auth_user_role', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranchUsers()
    {
        return $this->hasMany(BranchUser::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranches()
    {
        return $this->hasMany(Branch::className(), ['id' => 'branch_id'])->viaTable('branch_user', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuCooks()
    {
        return $this->hasMany(MenuCook::className(), ['cook_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::className(), ['id' => 'menu_id'])->viaTable('menu_cook', ['cook_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderAssets()
    {
        return $this->hasMany(OrderAsset::className(), ['cook_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderAssets0()
    {
        return $this->hasMany(OrderAsset::className(), ['waiter_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return User::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return User::findOne(['verification_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return User::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this ? $this->password_hash : \Yii::$app->security->generatePasswordHash(''));
    }

    public function hasPermission($permId)
    {
        $item = User::find()->innerJoin('auth_user_role', 'auth_user_role.user_id = auth_user.id')->innerJoin('auth_permission_role', 'auth_permission_role.role_id = auth_user_role.role_id')->where(['auth_user.id' => $this->id, 'auth_permission_role.perm_id' => $permId])->one();
        return $item;
    }

    public function isInBranch($branchId)
    {
        $item = BranchUser::find()->where(['user_id' => $this->id, 'branch_id' => $branchId])->one();
        return $item;
    }

    public static function findByIdPermAndBranch($id, $permId, $branchId)
    {
        return User::find()->innerJoin('branch_user', 'auth_user.id = branch_user.user_id')->innerJoin('auth_user_role', 'auth_user_role.user_id = auth_user.id')->innerJoin('auth_permission_role', 'auth_permission_role.role_id = auth_user_role.role_id')->where(['auth_user.id' => $id, 'auth_permission_role.perm_id' => $permId, 'branch_user.branch_id' => $branchId])->one();
    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public static function hasRole($userId, $roleId)
    {
        return AuthUserRole::findOne(['user_id' => $userId, 'role_id' => $roleId]);
    }

}
