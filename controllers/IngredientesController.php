<?php

namespace app\controllers;

use app\models\Ingredient;
use app\utilities\Utilities;
use Exception;

class IngredientesController extends MyRestController {

    public $modelClass = Ingredient::class;

    private function _getItems() {
        return Ingredient::findAll(['branch_id' => $this->requestParams['branch_id']]);
    }

    public function actionListar() {
        try {
            return ['code' => 'success', 'msg' => 'Datos cargados.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }

    public function actionCrear() {
        try {
            $item = new Ingredient(['name' => $this->requestParams['item']['name'], 'branch_id' => $this->requestParams['branch_id']]);
            if ($item->validate()) {
                $item->save();
                return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getItems()];
            }
            return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($item), 'data' => []];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionEditar() {
        try {
            $item = Ingredient::findOne($this->requestParams['item']['id']);
            if (!$item) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $item->setAttributes(['name' => $this->requestParams['item']['name']]);
            if ($item->validate()) {
                $item->save();
                return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getItems()];
            }
            return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($item), 'data' => []];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionEliminar() {
        try {
            $item = Ingredient::findOne($this->requestParams['id']);
            if (!$item) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $item->delete();
            return ['code' => 'success', 'msg' => 'Elemento eliminado.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

}
