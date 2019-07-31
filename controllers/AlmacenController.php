<?php

namespace app\controllers;

use app\models\Ingredient;
use app\models\MeasureUnit;
use app\models\Stock;
use app\utilities\Utilities;
use Exception;

class AlmacenController extends MyRestController {

    public $modelClass = Stock::class;

    private function _getItems() {
        $stockItems = Stock::find()->where(['branch_id' => $this->requestParams['branch_id']])->asArray()->all();
        foreach ($stockItems as &$stockItem) {
            $stockItem['ingredient_name'] = Ingredient::findOne($stockItem['ingredient_id'])->name;
            $stockItem['measure_unit_name'] = MeasureUnit::findOne($stockItem['measure_unit_id'])->name;
        }
        $ingredients = Ingredient::find()->where(['branch_id' => $this->requestParams['branch_id']])->asArray()->all();
        $measureUnits = MeasureUnit::find()->asArray()->all();
        return [$stockItems, $ingredients, $measureUnits];
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
            $item = new Stock([
                'quantity' => $this->requestParams['item']['quantity'],
                'measure_unit_id' => $this->requestParams['item']['measure_unit_id'],
                'ingredient_id' => $this->requestParams['item']['ingredient_id'],
                'branch_id' => $this->requestParams['branch_id'],
            ]);

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
            $item = Stock::findOne($this->requestParams['item']['id']);
            if (!$item) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $item->setAttributes([
                'quantity' => $this->requestParams['item']['quantity'],
                'measure_unit_id' => $this->requestParams['item']['measure_unit_id'],
                'ingredient_id' => $this->requestParams['item']['ingredient_id'],
            ]);
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
            $item = Stock::findOne($this->requestParams['id']);
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
