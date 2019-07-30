<?php

namespace app\controllers;

use app\models\Ingredient;
use Exception;

class IngredientesController extends MyRestController {

    public $modelClass = Ingredient::class;

    public function actionListar() {
        try {
            return ['code' => 'success', 'msg' => 'Datos cargados.', 'data' => Ingredient::findAll(['branch_id' => $this->requestParams['branch_id']])];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }

}
