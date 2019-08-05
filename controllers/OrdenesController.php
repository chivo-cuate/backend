<?php

namespace app\controllers;

use app\models\Asset;
use app\models\Branch;
use app\models\Order;
use app\models\User;
use app\utilities\Utilities;
use Exception;

class OrdenesController extends MyRestController {

    protected $assetTypeId;
    public $modelClass = Order::class;

    private function _findModel($id) {
        return Order::findOne(['id' => $id,]);
    }
    
    private function _getTableIndex($array, $tableNumber) {
        $arrayLength = count($array);
        for ($i = 0; $i < $arrayLength; $i++) {
            if ($array[$i]['table_number'] === $tableNumber) {
                return $i;
            }
        }
        return -1;
    }

    private function _initializeItems($branchTables) {
        $res = ['tables' => [], 'pending' => [], 'ready' => [], 'checked_out' => []];
        for ($i = 1; $i <= $branchTables; $i++) {
            $res['tables'][] = ['table_number' => $i, 'free' => 1];
        }
        return $res;
    }
    
    private function _getOrders($branchId, &$res) {
        $orders = Order::find()->where(['branch_id' => $branchId])->orderBy(['table_number' => SORT_ASC])->asArray()->all();
        foreach ($orders as $order) {
            $tableIndex = $this->_getTableIndex($res['tables'], $order['table_number']);
            $res['tables'][$tableIndex]['free'] = 0;
            $res['tables'][$tableIndex]['waiter'] = User::findOne($order['waiter_id'])->getFullName();
            switch ($order['status']) {
                case 0:
                    $res['pending'] = $order;
                    break;
                case 1:
                    $res['ready'] = $order;
                    break;
                default:
                    $res['complete'] = $order;
                    break;
            }
        }
    }
    
    private function _getMenuProducts(&$res) {
        $menu = Utilities::getCurrentMenu($this->requestParams['branch_id']);
        $res['assets'] = [];
        if ($menu) {
            $res['assets'] = Asset::find()
                ->select(['asset.id', 'asset.name', 'menu_asset.price', 'menu_asset.grams'])
                ->innerJoin('menu_asset', 'menu_asset.asset_id = asset.id')
                ->where(['menu_asset.menu_id' => $menu->id])
                ->orderBy(['asset.name' => SORT_ASC])
                //->where('asset.asset_type_id = 2 or (asset.asset_type_id = 1 and stock.quantity > 0)')
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

    public function actionListar() {
        try {
            return ['code' => 'success', 'msg' => 'Datos cargados.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }

    public function actionCrear() {
        try {
            
            return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($model), 'data' => []];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionEditar() {
        try {
            return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($model), 'data' => []];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionEliminar() {
        try {
            return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($model), 'data' => []];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

}
