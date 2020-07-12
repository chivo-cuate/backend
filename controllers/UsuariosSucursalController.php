<?php

namespace app\controllers;

use app\models\AuthRole;
use app\models\AuthUserRole;
use app\models\Branch;
use app\models\BranchUser;
use app\models\MenuCook;
use app\models\User;
use app\models\AuthUser;
use app\utilities\MenuHelper;
use app\utilities\Utilities;
use Exception;
use Yii;
use yii\helpers\ArrayHelper;
use function Lambdish\Phunctional\map;

class UsuariosSucursalController extends MyRestController {

    public $modelClass = User::class;

    public function actionListar() {
        try {
            return ['code' => 'success', 'msg' => 'Datos cargados.', 'data' => $this->_getUsers()];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }

    private function _getUsers() {
        $cooks = MenuHelper::getCurrentMenuCooks($this->requestParams['branch_id']);

        return map(function ($item) {
            $item['is_online'] = $item['session_id'] ? "Sí" : "No";
            return $item;
        }, $cooks);
    }

    public function actionDesconectar() {
        try {
            $userId = $this->requestParams['id'];
            $model = User::findOne($userId);

            if (!$model || $model->id === $this->userInfo['user']->id) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => $this->_getUsers()];
            }

            $currMenu = MenuHelper::getCurrentMenu($this->requestParams['branch_id']);
            $menuCook = $currMenu->getMenuCooks()
                ->where("cook_id = :user_id", ['user_id' => $userId])
                ->one();

            $menuCook->setAttribute("session_id", null);
            $menuCook->save();

            return ['code' => 'success', 'msg' => 'Elemento actualizado.', 'data' => $this->_getUsers()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

}
