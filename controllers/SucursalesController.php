<?php

namespace app\controllers;

use app\models\Branch;
use app\models\BranchUser;
use app\models\User;
use app\utilities\Utilities;
use Exception;
use yii\filters\VerbFilter;

class SucursalesController extends MyRestController {

    public $modelClass = Branch::class;

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'edit-item' => ['post'],
            ],
        ];
        return $behaviors;
    }

    private function _getBranchesAndManagers() {
        $branches = Branch::find()->all();
        $res = [];
        foreach ($branches as $branch) {
            $authUsers = $branch->getUsers()->all();
            $manager = null;
            foreach ($authUsers as $authUser) {
                $user = User::findOne($authUser->id);
                if ($user->hasRole(2)) {
                    $manager = $user;
                    break;
                }
            }
            $res[] = [
                'id' => $branch->id,
                'name' => $branch->name,
                'tables' => $branch->tables,
                'description' => $branch->description,
                'manager_id' => $manager ? $manager->id : null,
                'manager_name' => $manager ? $manager->username : null,
            ];
        }
        $managers = User::find()
                ->select('auth_user.id, auth_user.first_name, auth_user.last_name, auth_user.email, auth_user.username')
                ->innerJoin('auth_user_role', 'auth_user_role.user_id = auth_user.id')
                ->where(['auth_user_role.role_id' => 2])
                ->all();
        return ['items' => $res, 'managers' => $managers];
    }

    private function _getBranchManager(Branch $branch) {
        $user = User::find()
                ->select('auth_user.id, auth_user.first_name, auth_user.last_name, auth_user.email, auth_user.username')
                ->innerJoin('auth_user_role', 'auth_user_role.user_id = auth_user.id')
                ->innerJoin('branch_user', 'branch_user.user_id = auth_user.id')
                ->where(['auth_user_role.role_id' => 2])
                ->andWhere(['branch_user.branch_id' => $branch->id])
                ->one();
        return $user;
    }

    public function actionEliminar() {
        try {
            $branch = Branch::findOne($this->requestParams['id']);
            if (!$branch)
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            $branch->delete();
            return ['code' => 'success', 'msg' => 'Elemento eliminado.', 'data' => $this->_getBranchesAndManagers()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionEditar() {
        try {
            $params = $this->requestParams['item'];
            $item = Branch::findOne($params['id']);
            if (!$item) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $item->setAttributes(['name' => $params['name'], 'tables' => $params['tables'], 'description' => $params['description']]);
            if ($item->validate()) {
                $item->save();
            } else {
                return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($item), 'data' => []];
            }
            $newManager = User::findOne($params['manager_id']);
            if (!$newManager || !$newManager->hasRole(2)) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $currManager = $this->_getBranchManager($item);
            if ($currManager !== $newManager) {
                if ($currManager) {
                    $branchUser = BranchUser::findOne(['branch_id' => $item->id, 'user_id' => $currManager->id]);
                    $branchUser->delete();
                }
                $newBranchUser = new BranchUser(['branch_id' => $item->id, 'user_id' => $newManager->id]);
                $newBranchUser->save();
            }
            return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getBranchesAndManagers()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionCrear() {
        try {
            $params = $this->requestParams['item'];
            $item = new Branch(['name' => $params['name'], 'tables' => $params['tables'], 'description' => $params['description']]);
            if ($item->validate()) {
                $item->save();
            } else {
                return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($item), 'data' => []];
            }
            $newManager = User::findOne($params['manager_id']);
            if (!$newManager || !$newManager->hasRole(2)) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $newBranchUser = new BranchUser(['branch_id' => $item->id, 'user_id' => $newManager->id]);
            $newBranchUser->save();
            return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getBranchesAndManagers()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionListar() {
        try {
            return ['code' => 'success', 'msg' => 'Elementos cargados.', 'data' => $this->_getBranchesAndManagers()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

}
