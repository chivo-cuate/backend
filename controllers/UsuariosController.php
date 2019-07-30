<?php

namespace app\controllers;

use app\models\AuthRole;
use app\models\AuthUserRole;
use app\models\Branch;
use app\models\BranchUser;
use app\models\User;
use app\utilities\Utilities;
use Exception;
use Yii;

class UsuariosController extends MyRestController {

    public $modelClass = User::class;

    private function _getUsers() {
        $users = User::find()->select(['id', 'first_name', 'last_name', 'username', 'email', 'phone_number', 'ine', 'address', 'sex'])->asArray()->all();
        for ($i = 0; $i < count($users); $i++) {
            $users[$i]['password'] = null;
            $users[$i]['password_confirm'] = null;
            $userRoles = AuthUserRole::find()->where(['user_id' => $users[$i]['id']])->all();
            foreach ($userRoles as $userRole) {
                $users[$i]['roles'][] = strval($userRole->role_id);
            }
            $userBranches = BranchUser::find()->where(['user_id' => $users[$i]['id']])->all();
            foreach ($userBranches as $userBranch) {
                $users[$i]['branches'][] = strval($userBranch->branch_id);
            }
        }
        $roles = AuthRole::find()->select(['id', 'name'])->asArray()->all();
        $branches = Branch::find()->select(['id', 'name'])->asArray()->all();
        return [$users, $roles, $branches];
    }

    private function _updateUserBranches($userId) {
        $branchesParams = $this->requestParams['branches'];
        foreach ($branchesParams as $branchId) {
            $branch = Branch::findOne($branchId);
            if ($branch) {
                $branchUser = new BranchUser(['user_id' => $userId, 'branch_id' => $branchId]);
                $branchUser->save();
            }
        }
    }

    private function _updateUserRoles($userId) {
        $rolesParams = $this->requestParams['roles'];
        foreach ($rolesParams as $roleId) {
            $role = AuthRole::findOne($roleId);
            if ($role) {
                $userRole = new AuthUserRole(['user_id' => $userId, 'role_id' => $roleId]);
                $userRole->save();
            }
        }
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

    public function actionCrear() {
        try {
            $itemParams = $this->requestParams['item'];
            $item = new User([
                'first_name' => $itemParams['first_name'],
                'last_name' => $itemParams['last_name'],
                'username' => $itemParams['username'],
                'email' => $itemParams['email'],
                'address' => $itemParams['address'],
                'phone_number' => $itemParams['phone_number'],
                'ine' => $itemParams['ine'],
                'sex' => $itemParams['sex'],
                'password_hash' => null,
                'auth_key' => Yii::$app->security->generateRandomString(32),
                'verification_token' => Yii::$app->security->generateRandomString(32),
                'created_at' => time(),
                'updated_at' => time(),
            ]);

            if ($itemParams['password'] && $itemParams['password'] === $itemParams['password_confirm']) {
                $item->password_hash = Yii::$app->security->generatePasswordHash($itemParams['password']);
            }

            if ($item->validate()) {
                $item->save();
            } else {
                return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($item), 'data' => []];
            }

            $this->_updateUserBranches($item->id);
            $this->_updateUserRoles($item->id);

            return ['code' => 'success', 'msg' => 'Elemento adicionado.', 'data' => $this->_getUsers()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionEditar() {
        try {
            $itemParams = $this->requestParams['item'];
            $item = User::findOne($itemParams['id']);

            if (!$item) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => $this->_getUsers()];
            }

            if ($itemParams['password'] && $itemParams['password'] === $itemParams['password_confirm']) {
                $item->password_hash = Yii::$app->security->generatePasswordHash($itemParams['password']);
            }

            $item->setAttributes([
                'first_name' => $itemParams['first_name'],
                'last_name' => $itemParams['last_name'],
                'username' => $itemParams['username'],
                'email' => $itemParams['email'],
                'address' => $itemParams['address'],
                'phone_number' => $itemParams['phone_number'],
                'ine' => $itemParams['ine'],
                'sex' => $itemParams['sex']
            ]);
            if ($item->validate()) {
                $item->save();
            } else {
                return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($item), 'data' => []];
            }

            BranchUser::deleteAll(['user_id' => $item->id]);
            $this->_updateUserBranches($item->id);

            AuthUserRole::deleteAll(['user_id' => $item->id]);
            $this->_updateUserRoles($item->id);

            return ['code' => 'success', 'msg' => 'Elemento actualizado.', 'data' => $this->_getUsers()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

}
