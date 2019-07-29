<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\User;
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
                    ]];
                }

                return ['code' => 'error', 'msg' => 'Your data could not be encoded.', 'data' => []];
            }
            return ['code' => 'error', 'msg' => 'Credenciales incorrectas.', 'data' => null];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }
    
    public function actionUpdateProfile() {
        try {
            if ($this->userInfo['user']) {
                $user = User::findOne($this->userInfo['user']->id);
                $data = json_decode($this->requestParams['user_data'], true);
                $user->setAttributes($data);
                $user->save();
                if ($user->hasErrors()) {
                    $errors = '';
                    foreach ($user->getErrors() as $key => $value) {
                        $errors .= ($value[0] . ' ');
                    }
                    return ['code' => 'error', 'msg' => $errors, 'data' => null];
                }
                return ['code' => 'success', 'msg' => 'Datos actualizados.', 'data' => null];
            }
            return ['code' => 'error', 'msg' => 'Credenciales incorrectas.', 'data' => null];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => null];
        }
    }
    
    public function actionGetProfile() {
        try {
            if ($this->userInfo['user']) {
                $res = User::find()->select('id, first_name, last_name, username, email, phone_number')->where(['id' => $this->userInfo['user']->id])->one();
                return ['code' => 'success', 'msg' => 'Datos cargados.', 'data' => $res];
            }
            return ['code' => 'error', 'msg' => 'Credenciales incorrectas.', 'data' => null];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }
    
    public function actionChangePassword() {
        try {
            if ($this->userInfo['user']) {
                $user = User::findOne($this->userInfo['user']->id);
                $data = $this->requestParams;
                
                if (Yii::$app->security->validatePassword($data['current_password'], $this->userInfo['user']->password_hash)) {
                    if ($data['password'] === $data['password_confirm']) {
                        $newPassword = Yii::$app->security->generatePasswordHash($data['password']);
                        $this->userInfo['user']->password_hash = $newPassword;
                        $this->userInfo['user']->save();
                        return ['code' => 'success', 'msg' => 'Clave actualizada.', 'data' => null];
                    } else {
                        return ['code' => 'error', 'msg' => 'Las claves no coinciden.', 'data' => null];
                    }
                } else {
                    return ['code' => 'error', 'msg' => 'La clave actual no es correcta.', 'data' => null];
                }
            }
            return ['code' => 'error', 'msg' => 'Credenciales incorrectas.', 'data' => null];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => null];
        }
    }

}
