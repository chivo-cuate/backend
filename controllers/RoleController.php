<?php

namespace app\controllers;

use app\models\AuthRole;
use Exception;

class RoleController extends MyRestController {

    public $modelClass = AuthRole::class;

    public function beforeAction($action) {
        parent::beforeAction($action);
        return $this->userInfo['user'] ? true : false;
    }

    public function actionGetRoles() {
        try {
            if ($this->userInfo['user']->hasRole(1)) {
                $res = AuthRole::find()->all();
                return ['code' => 'success', 'msg' => 'Datos cargados.', 'data' => $res];
            }
            return ['code' => 'error', 'msg' => 'Credenciales incorrectas.', 'data' => null];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }

}
