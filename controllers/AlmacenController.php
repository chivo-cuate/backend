<?php

namespace app\controllers;

use app\models\Asset;
use app\models\AssetCategory;
use app\models\AssetType;
use app\models\MeasureUnit;
use app\models\Stock;
use app\utilities\Utilities;
use Exception;

class AlmacenController extends MyRestController {

    public $modelClass = Stock::class;

    public function actionListar() {
        try {
            return ['code' => 'success', 'msg' => 'Datos cargados.', 'data' => $this->_getReturnData()];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }

    private function _getReturnData() {
        return [$this->_getStockItems(), $this->_getActiveAssets(), MeasureUnit::find()->asArray()->all()];
    }

    private function _getStockItems() {
        $stockItems = Stock::find()->where(['branch_id' => $this->requestParams['branch_id']])->asArray()->all();
        foreach ($stockItems as &$stockItem) {
            $asset = Asset::findOne($stockItem['asset_id']);
            $stockItem['asset_name'] = $asset->name;
            $stockItem['measure_unit_name'] = MeasureUnit::findOne($stockItem['measure_unit_id'])->abbr;
            $stockItem['quantity_desc'] = $stockItem['quantity'] . " " . strtolower($stockItem['measure_unit_name']);
            $stockItem['type_name'] = AssetType::findOne($asset->asset_type_id)->name;
        }
        return $stockItems;
    }

    private function _getActiveAssets() {
        $assetTypes = AssetType::find()->orderBy(['name' => SORT_ASC])->all();
        $assets = [];

        foreach ($assetTypes as $assetType) {
            $assetsByType = Asset::find()
                ->where(['asset_type_id' => $assetType->id, 'status' => 1])
                ->asArray()
                ->all();
            if (count($assetsByType) > 0) {
                $assets[]['header'] = $assetType->name;
                foreach ($assetsByType as $assetByType) {
                    $assetCategory = AssetCategory::findOne($assetByType['category_id']);
                    if (!$assetCategory || $assetCategory->needs_cooking === 0) {
                        $assets[] = [
                            'id' => $assetByType['id'],
                            'name' => $assetByType['name'],
                            'needs_cooking' => $assetCategory ? $assetCategory->needs_cooking === 1 : false,
                            'group' => $assetType['name'],
                        ];
                    }
                }
                $assets[]['divider'] = true;
            }
        }
        if (count($assets) > 0) {
            unset($assets[count($assets) - 1]);
        }
        return $assets;
    }

    private function _getExisitingAssetInStock($assetId, $priceIn) {
        return Stock::findOne([
                    'asset_id' => $assetId,
                    'price_in' => $priceIn,
                    'branch_id' => $this->requestParams['branch_id'],
        ]);
    }

    public function actionCrear() {
        try {
            $existingModel = $this->_getExisitingAssetInStock($this->requestParams['item']['asset_id'], $this->requestParams['item']['price_in']);
            if ($existingModel) {
                $existingModel->quantity += $this->requestParams['item']['quantity'];
                $existingModel->save();
                return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getReturnData()];
            }
            $model = new Stock();
            $this->_setModelAttributes($model);
            if ($model->validate()) {
                $model->save();
                return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getReturnData()];
            }
            return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($model), 'data' => []];
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
                'price_in' => $this->requestParams['item']['price_in'],
                'asset_id' => $this->requestParams['item']['asset_id'],
            ]);
            if ($item->validate()) {
                $item->save();
                return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getReturnData()];
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
            return ['code' => 'success', 'msg' => 'Elemento eliminado.', 'data' => $this->_getReturnData()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

}
