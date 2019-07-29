<?php

namespace app\controllers;

use app\models\Ingredient;
use Exception;

class IngredientController extends MyRestController {

    public $modelClass = Ingredient::class;

    public function beforeAction($action) {
        parent::beforeAction($action);
        return $this->userInfo['user'] ? true : false;
    }

    public function actionGetIngredients() {
        try {
            if ($this->userInfo['user']->hasRole(2)) {
                $res = Ingredient::findAll(['branch_id' => $this->requestParams['branch_id']]);
                return ['code' => 'success', 'msg' => 'Datos cargados.', 'data' => $res];
            }
            return ['code' => 'error', 'msg' => 'Credenciales incorrectas.', 'data' => null];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }

}
