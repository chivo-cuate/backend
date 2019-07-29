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
 * @property string $phone_number
 * @property string $auth_key
 * @property string $verification_token
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AuthUserRole[] $authUserRoles
 * @property AuthRole[] $roles
 * @property BranchUser[] $branchUsers
 * @property Branch[] $branches
 * @property Order[] $orders
 */
class User extends ActiveRecord implements IdentityInterface {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'auth_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
                [['username', 'first_name', 'last_name', 'auth_key', 'verification_token', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
                [['status', 'created_at', 'updated_at'], 'integer'],
                [['username', 'first_name', 'last_name', 'phone_number', 'password_hash', 'email'], 'string', 'max' => 255],
                [['auth_key', 'verification_token', 'password_reset_token'], 'string', 'max' => 32],
                [['username'], 'unique', 'message' => 'Ya existe un usuario con ese nombre.'],
                [['auth_key'], 'unique'],
                [['verification_token'], 'unique'],
                [['email'], 'unique', 'message' => 'Ya existe un usuario con ese correo.'],
                [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'verification_token' => Yii::t('app', 'Verification Token'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthUserRoles() {
        return $this->hasMany(AuthUserRole::className(), ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRoles() {
        return $this->hasMany(AuthRole::className(), ['id' => 'role_id'])->viaTable('auth_user_role', ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBranchUsers() {
        return $this->hasMany(BranchUser::className(), ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBranches() {
        return $this->hasMany(Branch::className(), ['id' => 'branch_id'])->viaTable('branch_user', ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOrders() {
        return $this->hasMany(Order::className(), ['user_id' => 'id']);
    }

    public function getRolesArray() {
        $res = [];
        $roles = $this->getRoles()->select('name')->all();
        foreach ($roles as $role) {
            $res[] = $role['name'];
        }
        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id) {
        return User::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return User::findOne(['verification_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return User::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId() {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey) {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function hasRole($roleId) {
        return AuthUserRole::findOne(['user_id' => $this->id, 'role_id' => $roleId]);
    }

    public function getFullName() {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getUsersByRole($roleId) {
        return AuthUser::find()->join('INNER JOIN', 'auth_user_role', ['auth_user.id' => 'auth_user_role.user_id'], ['auth_user_role.role_id' => $roleId])->all();
    }

}
