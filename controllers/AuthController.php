<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\User;
use app\utilities\MenuHelper;
use app\utilities\Security;
use app\utilities\Utilities;
use app\utilities\UserHelper;
use Exception;
use Yii;

class AuthController extends MyRestController {

    public $modelClass = User::class;

    /**
     * {@inheritdoc}
     */
    private function _getJwtPayload($user) {
        $this->userInfo = ['code' => 'success', 'msg' => 'Credentials verified', 'user' => $user];
        $now = time();
        $homeUrl = Yii::$app->getUrlManager()->getBaseUrl();
        $payload = [
            'iss' => $homeUrl,
            'aud' => $homeUrl,
            'iat' => $now,
            'exp' => $now + 43200, //12 hours
            'user_id' => $this->userInfo['user']->id,
            'ip' => $this->request->getUserIP(),
        ];
        return $this->encodeJWT($payload);
    }

    private function _getRedirectRoute($currBranch) {
        if ($currBranch) {
            if ($this->userInfo['user']->hasPermission(25)) {
                return '/sucursal/menu-diario';
            } else if ($this->userInfo['user']->hasPermission(30) || $this->userInfo['user']->hasPermission(39)) {
                return '/clientes/ordenes';
            }
        }
        return null;
    }

    private function _generateLoginResponse($jwt) {
        $userBranches = $this->userInfo['user']->getBranches()->select('id, name, tables')->all();
        $currBranch = count($userBranches) === 1 ? $userBranches[0] : null;
        return [
            'code' => 'success',
            'msg' => 'Credenciales verificadas.',
            'data' => [
                'is_guest' => false,
                'user_id' => $this->userInfo['user']->id,
                'name' => $this->userInfo['user']->first_name,
                'branches' => $userBranches,
                'curr_branch' => $currBranch,
                'jwt' => $jwt,
                'roles' => UserHelper::getUserRolesNamesArray($this->userInfo['user']),
                'redirect' => $this->_getRedirectRoute($currBranch),
                'permissions' => Security::getUserPermissions($this->userInfo['user']),
                'cooks' => UserHelper::getCooksPerBranches($this->userInfo['user']),
            ]
        ];
    }

    public function actionLogin() {
        try {
            $loginFormModel = new LoginForm(['username' => $this->requestParams['username'], 'password' => $this->requestParams['password']]);
            $this->_exitIfValidationFails($loginFormModel);
            $jwt = $this->_getJwtPayload($loginFormModel->getAuthenticatedUser());
            if (!$jwt) {
                return ['code' => 'error', 'msg' => 'Your data could not be encoded.', 'data' => []];
            }
            return $this->_generateLoginResponse($jwt);
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionUpdateProfile() {
        try {
            $model = $this->userInfo['user'];
            $attribs = json_decode($this->requestParams['item'], true);
            $model->setAttributes($attribs, false);
            $this->_exitIfValidationFails($model);
            $model->save();
            return ['code' => 'success', 'msg' => 'Datos actualizados.', 'data' => []];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionGetProfile() {
        try {
            if ($this->userInfo['user']) {
                $res = User::find()->where(['id' => $this->userInfo['user']->id])->select(['first_name', 'last_name', 'username', 'email', 'ine', 'phone_number', 'address', 'sex'])->one();
                return ['code' => 'success', 'msg' => 'Datos cargados.', 'data' => $res];
            }
            return ['code' => 'error', 'msg' => 'Credenciales incorrectas.', 'data' => []];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }

    private function _exitIfPasswordsDoNotMatch($data) {
        if ($data['password'] !== $data['password_confirm']) {
            throw new Exception('Las claves no coinciden.');
        }
    }

    public function actionChangePassword() {
        try {
            $data = $this->requestParams;
            $this->_exitIfPasswordsDoNotMatch($data);
            if (!Yii::$app->security->validatePassword($data['current_password'], $this->userInfo['user']->password_hash)) {
                return ['code' => 'error', 'msg' => 'La clave actual no es correcta.', 'data' => []];
            }
            $newPassword = Yii::$app->security->generatePasswordHash($data['password']);
            $this->userInfo['user']->password_hash = $newPassword;
            $this->userInfo['user']->save();
            return ['code' => 'success', 'msg' => 'Clave actualizada.', 'data' => []];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionTest() {
        return UserHelper::getCooksPerBranches(User::findOne(10));
    }

}
