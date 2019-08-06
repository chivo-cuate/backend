<?php

namespace app\controllers;

use app\models\Asset;
use app\models\Branch;
use app\models\Order;
use app\models\OrderAsset;
use app\models\Stock;
use app\models\User;
use app\utilities\Utilities;
use Exception;
use Yii;

class OrdenesController extends MyRestController {

    protected $assetTypeId;
    public $modelClass = Order::class;

    private function _findModel($id) {
        return Order::findOne(['id' => $id,]);
    }

    private function _getTableIndex($array, $tableNumber) {
        $arrayLength = count($array);
        for ($i = 0; $i < $arrayLength; $i++) {
            if ($array[$i]['table_number'] == $tableNumber) {
                return $i;
            }
        }
        return -1;
    }

    private function _initializeItems($branchTables) {
        $res = ['tables' => []];
        for ($i = 1; $i <= $branchTables; $i++) {
            $res['tables'][] = ['table_number' => $i, 'status' => 0, 'assets' => [], 'order' => null];
        }
        return $res;
    }

    private function _getOrders($branchId, &$res) {
        $orders = Order::find()->where(['branch_id' => $branchId])->andWhere('status in (0, 1)')->orderBy(['table_number' => SORT_ASC])->all();
        foreach ($orders as $order) {
            $tableIndex = $this->_getTableIndex($res['tables'], $order->table_number);
            $isOwnOrder = $order->waiter_id === $this->userInfo['user']->id;
            $res['tables'][$tableIndex]['status'] = $order->status === 1 ? 2 : ($isOwnOrder ? 1 : -1);
            $res['tables'][$tableIndex]['waiter'] = $isOwnOrder ? null : User::findOne($order->waiter_id)->getFullName();
            $res['tables'][$tableIndex]['order'] = $isOwnOrder ? $order->getAttributes() : null;
            $res['tables'][$tableIndex]['assets'] = $isOwnOrder ? $order->getOrderAssets()->asArray()->all() : [];
        }
    }

    private function _getMenuProducts(&$res) {
        $menu = Utilities::getCurrentMenu($this->requestParams['branch_id']);
        $res['assets'] = [];
        if ($menu) {
            $res['assets'] = Asset::find()
                    ->select(['asset.id', 'asset.name', 'menu_asset.price', 'menu_asset.grams'])
                    ->innerJoin('menu_asset', 'menu_asset.asset_id = asset.id')
                    ->innerJoin('stock', 'stock.asset_id = asset.id')
                    ->where(['menu_asset.menu_id' => $menu->id])
                    ->andWhere(['asset.asset_type_id' => 2])
                    ->andWhere('stock.quantity > 0')
                    ->orderBy(['asset.name' => SORT_ASC])
                    ->asArray()
                    ->all();
        }
    }

    private function _getItems() {
        $branch = Branch::findOne($this->requestParams['branch_id']);
        $res = $this->_initializeItems($branch->tables);
        $this->_getOrders($branch->id, $res);
        $this->_getMenuProducts($res);
        return $res;
    }

    private function _updateOrderAssets(Order $model) {
        OrderAsset::deleteAll(['order_id' => $model->id]);
        foreach ($this->requestParams['assets'] as $assetValues) {
            $asset = Asset::findOne($assetValues['asset_id']);
            if ($asset) {
                $stockEntry = Stock::find()->where(['asset_id' => $asset->id, 'branch_id' => $asset->branch_id])->orderBy(['id' => SORT_ASC])->one();
                $orderAsset = new OrderAsset(['order_id' => $model->id, 'asset_id' => $asset->id, 'quantity' => $assetValues['quantity'], 'price_in' => $stockEntry ? $stockEntry->price_in : 0]);
                $orderAsset->save();
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
            $model = new Order(['date_time' => time(), 'status' => 0, 'waiter_id' => $this->userInfo['user']->id]);
            $this->_setModelAttributes($model);
            if (!$model->validate()) {
                return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($model), 'data' => []];
            }
            if (count($this->requestParams['assets']) < 1) {
                return ['code' => 'error', 'msg' => 'Debe incluir al menos un producto.', 'data' => []];
            }
            $model->save();
            $this->_updateOrderAssets($model);
            return ['code' => 'success', 'msg' => 'Operación realizada.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionEditar() {
        try {
            $model = Order::findOne($this->requestParams['item']['id']);
            if (!$model) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $this->_updateOrderAssets($model);
            return ['code' => 'success', 'msg' => 'Operación realizada.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionCerrar() {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = Order::findOne([
                        'id' => $this->requestParams['id'],
                        'table_number' => $this->requestParams['table_number'],
                        'branch_id' => $this->requestParams['branch_id'],
                        'waiter_id' => $this->userInfo['user']->id
            ]);
            
            if (!$model) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            
            $model->status = 2;
            $model->save();
            
            $orderAssets = $model->getOrderAssets()->all();
            foreach ($orderAssets as $orderAsset) {
                $asset = $orderAsset->getAsset()->one();
                $stockEntry = $asset->getStocks()->orderBy(['id' => SORT_ASC])->one();
                $stockEntry->quantity -= $orderAsset->quantity;
                $stockEntry->save();
            }
            $transaction->commit();
            return ['code' => 'success', 'msg' => 'Operación realizada.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            $transaction->rollBack();
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionEliminar() {
        try {
            $model = Order::findOne([
                        'id' => $this->requestParams['id'],
                        'table_number' => $this->requestParams['table_number'],
                        'branch_id' => $this->requestParams['branch_id'],
                        'waiter_id' => $this->userInfo['user']->id,
                        'status' => 0,
            ]);
            
            if (!$model) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $model->delete();
            return ['code' => 'success', 'msg' => 'Operación realizada.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

}
