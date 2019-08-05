<?php

namespace app\controllers;

use app\models\Asset;
use app\models\MenuAsset;
use app\models\OrderAsset;
use app\models\Stock;
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

    private function _activeInStock(Asset &$model) {
        $item = Stock::find()->where(['asset_id' => $model->id])->andWhere('quantity > 0')->one();
        if ($item) {
            $model->addError('id', 'Aun existen ' . $item->quantity . ' ' . $item->getMeasureUnit()->one()->name . ' este producto en el almacén.');
        }
    }

    private function _activeInOrders(Asset &$model) {
        $item = OrderAsset::findOne(['asset_id' => $model->id]);
        if ($item) {
            $model->addError('id', 'Existen órdenes con este producto.');
        }
    }

    private function _activeInMenu(Asset &$model, $lookForCurrentMenu) {
        $params = ['asset_id' => $model->id];
        if ($lookForCurrentMenu) {
            $currentMenu = Utilities::getCurrentMenu($this->requestParams['branch_id']);
            if ($currentMenu) {
                $params['menu_id'] = Utilities::getCurrentMenu($this->requestParams['branch_id'])->id;
            }
        }
        $item = MenuAsset::findOne($params);
        if ($item) {
            $model->addError('id', $lookForCurrentMenu ? 'Este producto se encuentra en el menú actual.' : 'Este producto fue incluido en el menú del ' . $item->getMenu()->one()->date);
        }
    }

    private function _canBeDisabled(Asset &$model) {
        $model->validate();
        if ($model->status === 0) {
            $this->_activeInStock($model);
            $this->_activeInMenu($model, true);
        }
    }

    private function _canBeDeleted(Asset &$model) {
        $model->validate();
        $this->_activeInStock($model);
        $this->_activeInMenu($model, false);
        $this->_activeInOrders($model);
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
            $model = new Asset(['status' => 1, 'asset_type_id' => $this->assetTypeId]);
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
            $model = $this->_findModel($this->requestParams['id']);
            if (!$model) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $model->status = 0;
            $this->_canBeDeleted($model, false);
            if (!$model->hasErrors()) {
                $model->delete();
                return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getItems()];
            }
            return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($model), 'data' => []];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

}
