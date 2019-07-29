<?php

namespace app\controllers;

use app\models\Branch;
use Exception;

class BranchController extends MyRestController {

    public $modelClass = Branch::class;

    public function beforeAction($action) {
        parent::beforeAction($action);
        return $this->userInfo['user'] ? true : false;
    }

    public function actionGetBranches() {
        try {
            if ($this->userInfo['user']->hasRole(1)) {
                $branches = Branch::find()->all();
                $res = [];
                foreach ($branches as $branch) {
                    $authUsers = $branch->getUsers()->all();
                    $manager = null;
                    foreach ($authUsers as $authUser) {
                        $user = \app\models\User::findOne($authUser->id);
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
                        'manager' => $manager ? $manager->getFullName() : null,
                    ];
                }
                return ['code' => 'success', 'msg' => 'Sucursales cargadas.', 'data' => $res];
            }
            return ['code' => 'error', 'msg' => 'Credenciales incorrectas.', 'data' => null];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }

}
