<?php

namespace app\controllers;

use app\models\Asset;
use app\models\Menu;
use app\utilities\Utilities;
use Exception;

class AssetsController extends MyRestController {

    protected $assetTypeId;
    public $modelClass = Asset::class;

    private function _findModel($id) {
        return Asset::findOne([
                    'id' => $id,
                    'asset_type_id' => $this->assetTypeId,
                    'branch_id' => $this->requestParams['branch_id'],
        ]);
    }

    private function _getItems() {
        $items = Asset::find()->where([
                    'asset_type_id' => $this->assetTypeId,
                    'branch_id' => $this->requestParams['branch_id'],
                ])->asArray()->all();
        foreach ($items as &$item) {
            $item['status'] = $item['status'] === '1' ? true : false;
            $item['status_name'] = $item['status'] ? 'Activo' : 'Inactivo';
        }
        return $items;
    }

    private function _canBeDisabled(Asset &$model) {
        $model->validate();
        if (!$model->status) {
            $stockEntries = $model->getStocks()->all();
            foreach ($stockEntries as $stockEntry) {
                if ($stockEntry->quantity > 0) {
                    $model->addError('name', 'Aun existen ' . $stockEntry->quantity . ' ' . $stockEntry->getMeasureUnit()->one()->name . ' de este producto en el almacén.');
                    break;
                }
            }
            $currMenu = Menu::find()->where(['branch_id' => $this->requestParams['branch_id']])->orderBy(['date' => SORT_DESC])->one();
            if ($currMenu) {
                $menuAssets = $currMenu->getAssets()->all();
                foreach ($menuAssets as $menuAsset) {
                    if ($menuAsset->asset_id === $model->id) {
                        $model->addError('name', 'Este producto se encuentra activo en el menú.');
                        break;
                    }
                }
            }
        }
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
            $model = new Asset(['status' => 1]);
            $this->_setModelAttributes($model);
            if (!$model->hasErrors()) {
                $model->save();
                return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getItems()];
            }
            return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($model), 'data' => []];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionEditar() {
        try {
            $model = $this->_findModel($this->requestParams['item']['id']);
            if (!$model) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $model->setAttributes([
                'name' => $this->requestParams['item']['name'],
                'status' => $this->requestParams['item']['status'] ? 1 : 0
            ]);
            $this->_canBeDisabled($model);
            if (!$model->hasErrors()) {
                $model->save();
                return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getItems()];
            }
            return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($model), 'data' => []];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionEliminar() {
        try {
            $item = $this->_findModel($this->requestParams['id']);
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
