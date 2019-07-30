<?php

namespace app\controllers;

use app\models\AuthRole;
use app\models\AuthUserRole;
use app\models\User;
use Exception;

class UsuariosController extends MyRestController {

    public $modelClass = User::class;

    private function _getUsers() {
        $users = User::find()->select(['id', 'first_name', 'last_name', 'username', 'email', 'phone_number'])->asArray()->all();
        for ($i = 0; $i < count($users); $i++) {
            $userRoles = AuthUserRole::find()->where(['user_id' => $users[$i]['id']])->all();
            foreach ($userRoles as $userRole) {
                $users[$i]['roles'][] = $userRole->role_id;
            }
        }
        $roles = AuthRole::find()->select(['id', 'name'])->asArray()->all();
        return [$users, $roles];
    }

    public function actionListar() {
        try {
            return ['code' => 'success', 'msg' => 'Datos cargados.', 'data' => $this->_getUsers()];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }

    public function actionEliminar() {
        try {
            $item = User::findOne($this->requestParams['id']);
            if (!$item) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $item->delete();
            return ['code' => 'success', 'msg' => 'Elemento eliminado.', 'data' => $this->_getUsers()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

}
