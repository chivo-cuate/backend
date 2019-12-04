<?php

namespace app\controllers;

use app\models\Asset;
use app\models\AssetComponent;
use app\models\AuthUser;
use app\models\Branch;
use app\models\Notification;
use app\models\Order;
use app\models\OrderAsset;
use app\models\Stock;
use app\models\User;
use app\utilities\Utilities;
use Exception;
use Yii;

class OrdenesController extends MyRestController {

    public $modelClass = Order::class;

    private function _initializeItems($branchTables) {
        $res = ['tables' => []];
        for ($i = 1; $i <= $branchTables; $i++) {
            $res['tables'][] = ['table_number' => $i, 'status_id' => -1];
        }
        return $res;
    }

    private function _cookingAnyAsset($assets) {
        foreach ($assets as $asset) {
            if ($asset['finished'] == '0' && $asset['cook_id'] == $this->userInfo['user']->id) {
                return true;
            }
        }
        return false;
    }

    private function _getGuiAttributes($statusId, $assets) {
        switch ($statusId) {
            case 0:
                return ['icon' => 'mdi-account-remove', 'icon_color' => 'error', 'edit' => true, 'delete' => true, 'checkout' => false, 'serve' => $this->_cookingAnyAsset($assets)];
            case 1:
                return ['icon' => 'mdi-fire', 'icon_color' => 'warning', 'edit' => true, 'delete' => true, 'checkout' => false, 'serve' => $this->_cookingAnyAsset($assets)];
            case 2:
                return ['icon' => 'local_dining', 'icon_color' => 'success', 'edit' => true, 'delete' => false, 'checkout' => true, 'serve' => false];
            default:
                return ['icon' => 'mdi-clipboard', 'icon_color' => 'grey'];
        }
    }

    private function _assetNeedsCooking(Asset $asset) {
        return Stock::findOne(['asset_id' => $asset->id]) ? false : true;
    }

    private function _updateTableOrdersCountByStatus(&$res, $tableIndex, $statusId, $guiAttribs) {
        if (isset($res['tables'][$tableIndex]['orders_status_count'][$statusId])) {
            $res['tables'][$tableIndex]['orders_status_count'][$statusId]['count'] ++;
        } else {
            $res['tables'][$tableIndex]['orders_status_count'][$statusId] = [
                'icon' => $guiAttribs['icon'],
                'icon_color' => $guiAttribs['icon_color'],
                'count' => 1,
            ];
        }
    }

    private function _getOrders($menu, &$res) {
        if ($menu) {
            $orders = Order::find()->where(['menu_id' => $menu->id])->andWhere('status_id != 3')->orderBy(['date_time' => SORT_ASC])->all();
            foreach ($orders as $order) {
                $tableIndex = $order->table_number - 1;
                $res['tables'][$tableIndex]['status_id'] = $order->status_id;
                $orderData = $order->getAttributes();
                $orderData['assets'] = $order->getOrderAssets()->orderBy(['finished' => SORT_DESC, 'id' => SORT_ASC])->asArray()->all();
                $orderData['status'] = $order->status->name;
                $orderData['slug'] = substr($order->status->slug, 0, 1);
                $guiAttribs = $this->_getGuiAttributes($order->status_id, $orderData['assets']);
                $this->_updateTableOrdersCountByStatus($res, $tableIndex, $order->status_id, $guiAttribs);
                $orderData['gui_attribs'] = $guiAttribs;
                $res['tables'][$tableIndex]['orders'][] = $orderData;
            }
        }
    }

    private function _getMenuProducts($menu, &$res) {
        $res['assets'] = [];
        $res['cooks_enabled'] = false;
        if ($menu) {
            $res['cooks_enabled'] = count($menu->getMenuCooks()->all()) > 0 ? true : false;
            $menuAssets = Asset::find()->select(['asset.id', 'asset.name', 'menu_asset.price', 'menu_asset.grams', 'asset_category.name as group'])->innerJoin('menu_asset', 'menu_asset.asset_id = asset.id')->innerJoin('asset_category', 'asset_category.id = asset.category_id')->where(['menu_asset.menu_id' => $menu->id])->orderBy(['group' => SORT_ASC, 'asset.name' => SORT_ASC])->asArray()->all();
            $lastGroup = null;
            $assets = [];
            $i = 0;
            foreach ($menuAssets as $menuAsset) {
                if ($menuAsset['group'] !== $lastGroup) {
                    $assets[]['divider'] = true;
                    $lastGroup = $menuAsset['group'];
                    $assets[]['header'] = $lastGroup;
                }
                $assets[] = $menuAsset;
                $i++;
            }
            $res['assets'] = $assets;
        }
    }

    private function _getItems() {
        $res = ['tables' => [], 'assets' => [], 'orders' => [], 'cooks' => [], 'cooks_enabled' => true];
        $menu = Utilities::getCurrentMenu($this->requestParams['branch_id']);
        if ($this->userInfo['user']->hasPermission(30)) {
            $branch = Branch::findOne($this->requestParams['branch_id']);
            $res = $this->_initializeItems($branch->tables);
            $this->_getOrders($menu, $res);
            $this->_getMenuProducts($menu, $res);
        }
        if ($this->userInfo['user']->hasPermission(39) && $menu) {
            $cooks = $menu->getCooks()->all();
            $orders = $this->_getPendingOrders($menu->id);
            $this->_getCurrentOrderForCooks($cooks);
            $res = array_merge($res, ['orders' => $orders, 'cooks' => $cooks]);
        }
        $res['notifications'] = $this->_getNotifications();
        return $res;
    }

    private function _exitIfNoAssetsInOrder() {
        if (count($this->requestParams['assets']) === 0) {
            return ['code' => 'error', 'msg' => 'Debe incluir al menos un producto.', 'data' => []];
        }
    }

    private function _verifyIfCookHasPermission(OrderAsset &$orderAsset, Asset $asset) {
        if ($orderAsset->cook_id) {
            $user = new User($orderAsset->cook->getAttributes());
            if ($this->_assetNeedsCooking($asset) && !$user->hasPermission(34)) {
                $orderAsset->cook_id = null;
            } else if (!$this->_assetNeedsCooking($asset) && $this->userInfo['user']->hasPermission(31)) {
                $orderAsset->cook_id = $this->userInfo['user']->id;
            }
        }
    }

    private function _updateOrderAssets(Order $model) {
        $this->_exitIfNoAssetsInOrder();
        if ($model->isNewRecord) {
            $model->save();
        } else {
            OrderAsset::deleteAll(['order_id' => $model->id, 'finished' => 0]);
        }
        $finishedAssets = 0;
        foreach ($this->requestParams['assets'] as $assetValues) {
            $finishedAssets += $assetValues['finished'];
            if ($assetValues['finished'] === '0') {
                $asset = Asset::findOne($assetValues['asset_id']);
                $orderAsset = new OrderAsset(['order_id' => $model->id, 'asset_id' => $asset->id, 'finished' => 0]);
                $orderAsset->setAttributes($assetValues);
                $orderAsset->waiter_id = $orderAsset->waiter_id ? $orderAsset->waiter_id : $this->userInfo['user']->id;
                $this->_verifyIfCookHasPermission($orderAsset, $asset);
                $orderAsset->save();
            }
        }
        $this->_updateOrderStatusByFinishedAssets($model, count($this->requestParams['assets']), $finishedAssets);
    }

    private function _updateOrderStatusByFinishedAssets(Order $model, $assetsCount, $compareValue) {
        if ($assetsCount === $compareValue) {
            $model->status_id = 2;
            $model->save();
        }
    }

    private function _getFirstAvailableCook() {
        $menu = Utilities::getCurrentMenu($this->requestParams['branch_id']);
        $menuCooks = $menu->getMenuCooks()->orderBy(['cook_id' => SORT_ASC])->all();
        $activeOrders = OrderAsset::find()->innerJoin('order', 'order_asset.order_id = order.id')->innerJoin('menu', 'order.menu_id = menu.id')->where(['menu.id' => $menu->id, 'order_asset.finished' => 0])->all();
        foreach ($menuCooks as $menuCook) {
            $cookAvailable = true;
            foreach ($activeOrders as $activeOrder) {
                if ($activeOrder->cook_id === $menuCook->cook_id) {
                    $cookAvailable = false;
                    break;
                }
            }
            if ($cookAvailable) {
                return User::findOne($menuCook->cook_id);
            }
        }
        return null;
    }

    private function _getNextOrderNumber($menuId, $tableNumber) {
        $maxNumber = Order::find()->where(['menu_id' => $menuId, 'table_number' => $tableNumber])->andWhere('status_id <> 3')->max('[[order_number]]');
        return $maxNumber ? $maxNumber + 1 : 1;
    }

    private function _notifyUsers($model, $waiterId, $cook) {
        switch ($model->status_id) {
            case 0:
                $this->createNotification('Orden en cola', "La orden {$model->order_number} de la mesa {$model->table_number} se encuentra en cola.", date('Y-m-d h:i'), $waiterId, $model->id);
                break;
            case 1:
                if ($cook) {
                    $cookName = $cook->getFullName();
                    $cookGenderEnding = $cook->sex === 'M' ? 'o' : 'a';
                    $this->createNotification('Orden asignada', "{$cookName} ha sido asignad{$cookGenderEnding} a la orden {$model->order_number} de la mesa {$model->table_number}.", date('Y-m-d h:i'), $waiterId, $model->id);
                    $menu = Utilities::getCurrentMenu($this->requestParams['branch_id']);
                    $menuCooks = $menu->getCooks()->all();
                    foreach ($menuCooks as $menuCook) {
                        $this->createNotification('Orden asignada', "{$cookName} ha sido asignad{$cookGenderEnding} a la orden {$model->order_number} de la mesa {$model->table_number}.", date('Y-m-d h:i'), $menuCook->id, $model->id);
                    }
                }
                break;
            case 2:
                $this->createNotification('Orden elaborada', "La orden {$model->order_number} de la mesa {$model->table_number} está lista para servir.", date('Y-m-d h:i'), $waiterId, $model->id);
                break;
            default:
                break;
        }
    }

    private function _assignOrderAssetsToCookOrWaiter(Order &$model) {
        $orderAssets = $model->getOrderAssets()->where(['finished' => 0])->all();
        $assetsCount = count($orderAssets);
        if ($assetsCount > 0) {
            $cook = null;
            $waiterId = $orderAssets[0]->waiter_id;
            $assignedAssetsCount = 0;
            $assignedAssetsToWaiterCount = 0;
            foreach ($orderAssets as &$orderAsset) {
                if (!$orderAsset->cook_id) {
                    $asset = $orderAsset->getAsset()->one();
                    if ($this->_assetNeedsCooking($asset)) {
                        if (!$cook) {
                            $cook = $this->_getFirstAvailableCook();
                            if ($cook) {
                                $orderAsset->cook_id = $cook->id;
                                $assignedAssetsCount ++;
                            }
                        }
                    } else {
                        $orderAsset->cook_id = $waiterId;
                        $orderAsset->finished = 1;
                        $assignedAssetsToWaiterCount ++;
                        $assignedAssetsCount ++;
                    }
                    $orderAsset->save();
                } else {
                    $assignedAssetsCount ++;
                }
            }
            $model->status_id = ($assignedAssetsToWaiterCount === $assetsCount ? 2 : ($assignedAssetsCount === $assetsCount ? 1 : 0));
            if ($model->save()) {
                $this->_notifyUsers($model, $waiterId, $cook);
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
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $menu = Utilities::getCurrentMenu($this->requestParams['branch_id']);
            $model = new Order(['date_time' => time(), 'status_id' => 0, 'menu_id' => $menu->id, 'order_number' => $this->_getNextOrderNumber($menu->id, $this->requestParams['item']['table_number'])]);
            $this->_setModelAttributes($model);
            if (!$model->validate()) {
                return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($model), 'data' => []];
            }
            $this->_updateOrderAssets($model);
            $this->_assignOrderAssetsToCookOrWaiter($model);
            $transaction->commit();
            return ['code' => 'success', 'msg' => 'Operación realizada.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            $transaction->rollBack();
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
            $this->_assignOrderAssetsToCookOrWaiter($model);
            $transaction->commit();
            return ['code' => 'success', 'msg' => 'Operación realizada.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            $transaction->rollBack();
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    private function _reduceIngredients(Asset $asset, $quantity) {
        $ingredients = AssetComponent::findAll(['asset_id' => $asset->id]);
        foreach ($ingredients as $ingredient) {
            $stockEntry = Stock::find()->where(['asset_id' => $ingredient->component_id, 'measure_unit_id' => $ingredient->measure_unit_id])->orderBy(['id' => SORT_ASC])->one();
            $stockEntry->quantity -= ($ingredient->quantity * $quantity);
            $stockEntry->save();
        }
    }

    private function _reduceStockAssetsByOrder(Order $model) {
        $orderAssets = $model->getOrderAssets()->all();
        foreach ($orderAssets as $orderAsset) {
            $asset = $orderAsset->getAsset()->one();
            if ($this->_assetNeedsCooking($asset)) {
                $this->_reduceIngredients($asset, $orderAsset->quantity);
            } else {
                $stockEntry = $asset->getStocks()->orderBy(['id' => SORT_ASC])->one();
                $stockEntry->quantity -= $orderAsset->quantity;
                $stockEntry->save();
            }
        }
    }

    private function _assignNextPendingOrder() {
        $menu = Utilities::getCurrentMenu($this->requestParams['branch_id']);
        $model = Order::find()->where(['menu_id' => $menu->id, 'status_id' => 0])->orderBy(['date_time' => SORT_ASC])->one();
        if ($model) {
            $this->_assignOrderAssetsToCookOrWaiter($model);
        }
    }

    public function actionElaborar() {
        try {
            $model = Order::find()->where(['id' => $this->requestParams['id'], 'table_number' => $this->requestParams['table_number'], 'status_id' => 1])->one();
            if (!$model) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $this->_serveOrderAssets($model, $this->requestParams['cook_id']);
            $this->_assignNextPendingOrder();
            return ['code' => 'success', 'msg' => 'Operación realizada.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionCerrar() {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = Order::findOne(['id' => $this->requestParams['id'], 'table_number' => $this->requestParams['table_number'], 'status_id' => 2]);

            if (!$model) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $model->status_id = 3;
            $model->save();
            $this->_reduceStockAssetsByOrder($model);
            Notification::deleteAll(['order_id' => $model->id]);
            $transaction->commit();
            return ['code' => 'success', 'msg' => 'Operación realizada.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            $transaction->rollBack();
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionEliminar() {
        try {
            $model = Order::find()->where(['id' => $this->requestParams['id'], 'table_number' => $this->requestParams['table_number']])->andWhere('status_id in (0, 1)')->one();
            if (!$model) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $model->delete();
            $this->_assignNextPendingOrder();
            //$maxNumber = Order::find()->where(['menu_id' => $menuId, 'table_number' => $tableNumber])->andWhere('status_id <> 3')->max('order_number');
            return ['code' => 'success', 'msg' => 'Operación realizada.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionServirProductos() {
        try {
            $model = Order::find()->where(['id' => $this->requestParams['id'], 'table_number' => $this->requestParams['table_number']])->andWhere('status_id in (0, 1)')->one();
            if (!$model) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $this->_serveOrderAssets($model, $this->userInfo['user']->id);
            return ['code' => 'success', 'msg' => 'Operación realizada.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionVerPendientes() {
        try {
            return ['code' => 'success', 'msg' => 'Datos cargados.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }

    private function _serveOrderAssets(Order $model, $cookId) {
        $orderAssets = $model->getOrderAssets()->where(['finished' => 0])->all();
        $i = 0;
        $waiterId = null;
        foreach ($orderAssets as $orderAsset) {
            if ($orderAsset->cook_id === $cookId) {
                $waiterId = $orderAsset->waiter_id;
                $orderAsset->finished = 1;
                $orderAsset->save();
                $i++;
            }
        }
        if (count($orderAssets) === $i) {
            $model->status_id = 2;
            $model->save();
        }
        $cook = User::findOne($cookId);
        $this->createNotification('Orden elaborada', "Un pedido de la orden {$model->order_number} de la mesa {$model->table_number}, elaborado por {$cook->getFullName()}, está listo para servir.", date('Y-m-d h:i'), $waiterId, $model->id);
    }

    private function _getPendingOrders($menuId) {
        $orders = Order::find()->where(['menu_id' => $menuId, 'status_id' => 0])->orderBy(['date_time' => SORT_ASC])->all();
        $res = [];
        $i = 0;
        foreach ($orders as &$order) {
            $res[$i] = $order->getAttributes();
            $res[$i]['elapsed_time'] = Utilities::dateDiff($order->date_time, time());
            $res[$i]['assets'] = [];
            $orderAssets = OrderAsset::find()->innerJoin('asset', 'order_asset.asset_id = asset.id')->where(['order_id' => $order->id])->select(['order_asset.asset_id', 'asset.name', 'order_asset.quantity', 'order_asset.finished'])->asArray()->all();
            foreach ($orderAssets as $orderAsset) {
                $asset = Asset::findOne($orderAsset['asset_id']);
                if ($orderAsset['finished'] === '0' && $this->_assetNeedsCooking($asset)) {
                    $res[$i]['assets'][] = $orderAsset;
                }
            }
            $i++;
        }
        return $res;
    }

    private function _getCurrentOrderForCooks(&$cooks) {
        $res = [];
        $i = 0;
        foreach ($cooks as &$cook) {
            $res[$i] = $cook->getAttributes();
            $orderAssets = OrderAsset::find()->innerJoin('asset', 'order_asset.asset_id = asset.id')->where(['order_asset.cook_id' => $cook->id, 'order_asset.finished' => 0])->select(['order_asset.order_id', 'order_asset.quantity', 'asset.name as asset_name'])->asArray()->all();
            if ($orderAssets) {
                $order = Order::findOne($orderAssets[0]['order_id'])->getAttributes();
                $order['elapsed_time'] = Utilities::dateDiff($order['date_time'], time());
                $order['assets'] = $orderAssets;
            } else {
                $order = null;
            }
            $res[$i++]['current_order'] = $order;
        }
        $cooks = $res;
    }

}
