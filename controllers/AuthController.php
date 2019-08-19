<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\User;
use app\utilities\Security;
use app\utilities\Utilities;
use Exception;
use Yii;

class AuthController extends MyRestController {

    public $modelClass = User::class;

    /**
     * {@inheritdoc}
     */
    public function actionLogin() {
        try {
            $loginFormModel = new LoginForm(['username' => $this->requestParams['username'], 'password' => $this->requestParams['password']]);
            if ($loginFormModel->validate()) {
                $this->userInfo = ['code' => 'success', 'msg' => 'Credentials verified', 'user' => $loginFormModel->getAuthenticatedUser()];
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
                $jwt = $this->encodeJWT($payload);

                if ($jwt) {
                    $userBranches = $this->userInfo['user']->getBranches()->select('id, name, tables')->all();
                    $currBranch = count($userBranches) === 1 ? $userBranches[0] : null;

                    return ['code' => 'success', 'msg' => 'Credenciales verificadas.', 'data' => [
                            'is_guest' => false,
                            'name' => $this->userInfo['user']->first_name,
                            'curr_branch' => $currBranch,
                            'branches' => $userBranches,
                            'jwt' => $jwt,
                            'roles' => $this->userInfo['user']->getRolesArray(),
                            'permissions' => Security::getUserPermissions($this->userInfo['user']),
                    ]];
                }

                return ['code' => 'error', 'msg' => 'Your data could not be encoded.', 'data' => []];
            }
            return ['code' => 'error', 'msg' => 'Credenciales incorrectas.', 'data' => []];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }

    public function actionUpdateProfile() {
        try {
            $model = $this->userInfo['user'];
            $attribs = json_decode($this->requestParams['item'], true);
            $model->setAttributes($attribs, false);
            if (!$model->validate()) {
                return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($model), 'data' => []];
            }
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

            if (Yii::$app->security->validatePassword($data['current_password'], $this->userInfo['user']->password_hash)) {
                $this->_exitIfPasswordsDoNotMatch($data);
                $newPassword = Yii::$app->security->generatePasswordHash($data['password']);
                $this->userInfo['user']->password_hash = $newPassword;
                $this->userInfo['user']->save();
                return ['code' => 'success', 'msg' => 'Clave actualizada.', 'data' => []];
            } else {
                return ['code' => 'error', 'msg' => 'La clave actual no es correcta.', 'data' => []];
            }
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

}
