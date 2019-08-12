<?php

namespace app\controllers;

use app\models\Asset;
use app\models\Branch;
use app\models\Order;
use app\models\OrderAsset;
use app\utilities\Utilities;
use Exception;
use Yii;

class OrdenesController extends MyRestController {

    public $modelClass = Order::class;

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
            $res['tables'][] = ['table_number' => $i, 'status_id' => -1];
        }
        return $res;
    }

    private function _getListAttribs($statusId) {
        switch ($statusId) {
            case 0:
                return ['icon' => 'mdi-clipboard-alert', 'badge_color' => 'error', 'edit' => true, 'delete' => true, 'close' => false];
            case 1:
                return ['icon' => 'mdi-clipboard-account', 'badge_color' => 'warning', 'edit' => true, 'delete' => true, 'close' => false];
            case 2:
                return ['icon' => 'mdi-clipboard-check', 'badge_color' => 'info', 'edit' => true, 'delete' => false, 'close' => true];
            default:
                return ['icon' => 'mdi-clipboard', 'badge_color' => 'grey'];
        }
    }

    private function _getOrders($branchId, &$res) {
        $orders = Order::find()->where(['branch_id' => $branchId])->andWhere('status_id != 3')->orderBy(['date_time' => SORT_ASC])->all();
        foreach ($orders as $order) {
            $listAttribs = $this->_getListAttribs($order->status_id);
            $orderInfo = $order->getAttributes();
            $orderInfo['assets'] = $order->getOrderAssets()->asArray()->all();
            $orderInfo['attribs'] = $listAttribs;
            $tableIndex = $this->_getTableIndex($res['tables'], $order->table_number);
            $res['tables'][$tableIndex]['status_id'] = $order->status_id;
            $res['tables'][$tableIndex]['ordersByCategory'][$order->status_id]['list_name'] = $order->status->name;
            $res['tables'][$tableIndex]['ordersByCategory'][$order->status_id]['list_icon'] = $listAttribs['icon'];
            $res['tables'][$tableIndex]['ordersByCategory'][$order->status_id]['badge_icon'] = substr($order->status->name, 0, 1);
            $res['tables'][$tableIndex]['ordersByCategory'][$order->status_id]['badge_color'] = $listAttribs['badge_color'];
            $res['tables'][$tableIndex]['ordersByCategory'][$order->status_id]['orders'][] = $orderInfo;
        }
    }

    private function _getMenuProducts(&$res) {
        $menu = Utilities::getCurrentMenu($this->requestParams['branch_id']);
        $res['assets'] = [];
        if ($menu) {
            $menuAssets = Asset::find()->select(['asset.id', 'asset.name', 'menu_asset.price', 'menu_asset.grams', 'asset_category.name as group'])->innerJoin('menu_asset', 'menu_asset.asset_id = asset.id')->innerJoin('asset_category', 'asset_category.id = asset.category_id')->where(['menu_asset.menu_id' => $menu->id])->orderBy(['group' => SORT_ASC, 'asset.name' => SORT_ASC])->asArray()->all();
            $lastGroup = null;
            $assets = [];
            $i = 0;
            foreach ($menuAssets as $menuAsset) {
                $groupChanged = false;
                if ($menuAsset['group'] !== $lastGroup) {
                    $assets[]['divider'] = true;
                    $lastGroup = $menuAsset['group'];
                    $assets[]['header'] = $lastGroup;
                    $groupChanged = true;
                }
                $assets[] = $menuAsset;
                $i++;
            }
            $res['assets'] = $assets;
        }
    }

    private function _getItems() {
        $branch = Branch::findOne($this->requestParams['branch_id']);
        $res = $this->_initializeItems($branch->tables);
        $this->_getOrders($branch->id, $res);
        $this->_getMenuProducts($res);
        return $res;
    }

    private function _exitIfNoAssetsInOrder() {
        if (count($this->requestParams['assets']) === 0) {
            return ['code' => 'error', 'msg' => 'Debe incluir al menos un producto.', 'data' => []];
        }
    }

    private function _updateOrderAssets(Order $model) {
        $this->_exitIfNoAssetsInOrder();
        if ($model->isNewRecord) {
            $model->save();
        } else {
            OrderAsset::deleteAll(['order_id' => $model->id, 'finished' => 0]);
        }
        foreach ($this->requestParams['assets'] as $assetValues) {
            if ($assetValues['finished'] === '0') {
                $asset = Asset::findOne($assetValues['asset_id']);
                $orderAsset = new OrderAsset(['order_id' => $model->id, 'asset_id' => $asset->id, 'quantity' => $assetValues['quantity'], 'waiter_id' => $this->userInfo['user']->id, 'finished' => 0]);
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
            $model = new Order(['date_time' => time(), 'status_id' => 0]);
            $this->_setModelAttributes($model);
            if (!$model->validate()) {
                return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($model), 'data' => []];
            }
            $this->_updateOrderAssets($model);
            return ['code' => 'success', 'msg' => 'Operación realizada.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionEditar() {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = Order::findOne($this->requestParams['item']['id']);
            if (!$model) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $this->_updateOrderAssets($model);
            $model->status_id = 0;
            $model->save();
            $transaction->commit();
            return ['code' => 'success', 'msg' => 'Operación realizada.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            $transaction->rollBack();
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionCerrar() {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = Order::findOne(['id' => $this->requestParams['id'], 'table_number' => $this->requestParams['table_number'], 'branch_id' => $this->requestParams['branch_id'],
            ]);

            if (!$model) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }

            $model->status_id = 3;
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
            $model = Order::find()->where([
                        'id' => $this->requestParams['id'],
                        'table_number' => $this->requestParams['table_number'],
                        'branch_id' => $this->requestParams['branch_id'],
                    ])->andWhere('status_id in (0, 1)')->one();

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
