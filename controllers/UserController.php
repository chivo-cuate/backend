<?php

namespace app\controllers;

use app\models\User;
use Exception;

class UserController extends MyRestController {

    public $modelClass = User::class;

    public function beforeAction($action) {
        parent::beforeAction($action);
        return $this->userInfo['user'] ? true : false;
    }

    public function actionGetUsers() {
        try {
            if ($this->userInfo['user']->hasRole(1)) {
                $res = User::find()->select('id, first_name, last_name, username, email, phone_number')->all();
                return ['code' => 'success', 'msg' => 'Datos cargados.', 'data' => $res];
            }
            return ['code' => 'error', 'msg' => 'Credenciales incorrectas.', 'data' => null];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }

}
