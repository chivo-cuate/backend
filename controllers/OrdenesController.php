<?php

namespace app\controllers;

use app\models\Asset;
use app\models\AssetComponent;
use app\models\Branch;
use app\models\MenuCook;
use app\models\Notification;
use app\models\Order;
use app\models\OrderAsset;
use app\models\OrderType;
use app\models\Stock;
use app\models\User;
use app\utilities\MenuHelper;
use app\utilities\Utilities;
use Exception;
use Yii;

class OrdenesController extends MyRestController
{

    public $modelClass = Order::class;

    private function _getItems()
    {
        $res = ['tables' => [], 'assets' => [], 'orders' => [], 'cooks' => [], 'cooks_enabled' => true];
        $menu = MenuHelper::getCurrentMenu($this->requestParams['branch_id']);
        $branch = Branch::findOne($this->requestParams['branch_id']);

        if ($this->userInfo['user']->hasPermission(30)) {
            $res = $this->_initializeItems($branch->tables);
            $this->_getOrders($menu, $res);
            $this->_getMenuProducts($menu, $res);
        }
        if ($this->userInfo['user']->hasPermission(39) && $menu) {
            $cooksIDs = $this->requestParams['cooks'];
            $cooks = MenuHelper::getCurrentMenuCooksActiveRecord($branch->id)
                ->where("auth_user.id in ($cooksIDs) and menu_cook.session_id is not null")
                ->asArray()
                ->all();
            $orders = $this->_getPendingOrders($menu->id);
            $this->_getCurrentOrderForCooks($cooks, $menu->id);
            $res = array_merge($res, ['orders' => $orders, 'cooks' => $cooks]);
            $res['notifications'] = $this->_getNotifications($cooksIDs);
        } else {
            $res['notifications'] = $this->_getNotifications();
        }

        return $res;
    }

    private function _initializeItems($branchTables)
    {
        $res = ['tables' => []];
        for ($i = 1; $i <= $branchTables; $i++) {
            $res['tables'][] = ['table_number' => $i, 'status_id' => -1];
        }
        return $res;
    }

    private function _cookingAnyAsset($assets)
    {
        foreach ($assets as $asset) {
            if ($asset['finished'] == '0' && $asset['cook_id'] == $this->userInfo['user']->id) {
                return true;
            }
        }
        return false;
    }

    private function _getGuiAttributes($statusId, $assets)
    {
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

    private function _updateTableOrdersCountByStatus(&$res, $tableIndex, $statusId, $guiAttribs)
    {
        if (isset($res['tables'][$tableIndex]['orders_status_count'][$statusId])) {
            $res['tables'][$tableIndex]['orders_status_count'][$statusId]['count']++;
        } else {
            $res['tables'][$tableIndex]['orders_status_count'][$statusId] = [
                'icon' => $guiAttribs['icon'],
                'icon_color' => $guiAttribs['icon_color'],
                'count' => 1,
            ];
        }
    }

    private function _getOrders($menu, &$res)
    {
        if ($menu) {
            $orders = Order::find()->where(['menu_id' => $menu->id])->andWhere('status_id != 3')->orderBy(['date_time' => SORT_ASC])->all();
            $res['takeaway_orders'] = [];
            foreach ($orders as $order) {
                $orderData = $order->getAttributes();
                $orderData['assets'] = $order->getOrderAssets()->orderBy(['finished' => SORT_DESC, 'id' => SORT_ASC])->asArray()->all();
                $orderData['status'] = $order->status->name;
                $orderData['elapsed_time'] = Utilities::dateDiff($order->date_time, time());
                $orderData['slug'] = substr($order->status->slug, 0, 1);
                $guiAttribs = $this->_getGuiAttributes($order->status_id, $orderData['assets']);
                $orderData['gui_attribs'] = $guiAttribs;

                if ($order->order_type_id === 1) {
                    $tableIndex = $order->table_number - 1;
                    $res['tables'][$tableIndex]['status_id'] = $order->status_id;
                    $this->_updateTableOrdersCountByStatus($res, $tableIndex, $order->status_id, $guiAttribs);
                    $res['tables'][$tableIndex]['orders'][] = $orderData;
                } else {
                    $res['takeaway_orders'][] = $orderData;
                }
            }
        }
    }

    private function _getMenuProducts($menu, &$res)
    {
        $res['assets'] = [];
        $res['cooks_enabled'] = false;
        if ($menu) {
            $res['cooks_enabled'] = $menu->getMenuCooks()->where('session_id is not null')->count() > 0;
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

    private function _exitIfNoAssetsInOrder()
    {
        if (count($this->requestParams['assets']) === 0) {
            return ['code' => 'error', 'msg' => 'Debe incluir al menos un producto.', 'data' => []];
        }
    }

    private function _verifyIfCookHasPermission(OrderAsset &$orderAsset, Asset $asset)
    {
        if ($orderAsset->cook_id) {
            $user = new User($orderAsset->cook->getAttributes());
            if ($this->_assetNeedsCooking($asset) && !$user->hasPermission(34)) {
                $orderAsset->cook_id = null;
            } else if (!$this->_assetNeedsCooking($asset) && $this->userInfo['user']->hasPermission(31)) {
                $orderAsset->cook_id = $this->userInfo['user']->id;
            }
        }
    }

    private function _assetNeedsCooking(Asset $asset)
    {
        return AssetComponent::findOne(['asset_id' => $asset->id]) ? true : false;
    }

    private function _updateOrderAssets(Order $model)
    {
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

    private function _updateOrderStatusByFinishedAssets(Order $model, $assetsCount, $compareValue)
    {
        if ($assetsCount === $compareValue) {
            $model->status_id = 2;
            $model->save();
        }
    }

    private function _getFirstAvailableCook($orderTypeId)
    {
        $menu = MenuHelper::getCurrentMenu($this->requestParams['branch_id']);
        $allowedCookRoles = $orderTypeId === 1 ? "4,6" : "6";

        $result = $menu->getMenuCooks()
            ->where("session_id is not null")
            ->innerJoin("auth_user_role", "menu_cook.cook_id = auth_user_role.user_id and role_id in ($allowedCookRoles)")
            ->andWhere("cook_id not in (select cook_id from order_asset where finished = 0 and cook_id is not null)")
            ->orderBy(['auth_user_role.role_id' => SORT_ASC])
            ->asArray()
            ->one();

        /*$sql = "select id from auth_user where
                id in (select cook_id from menu_cook where menu_id = {$menu->id} and session_id is not null)
                and id in (select user_id from auth_user_role where role_id in ($allowedCookRoles))
                and id not in (select cook_id from order_asset where finished = 0 and cook_id is not null) order by id";
        $command = Yii::$app->db->createCommand($sql);
        $result = $command->queryOne();*/
        if ($result && count($result) > 0) {
            return User::findOne($result['cook_id']);
        }
        return null;

        /*$menuCooks = $menu->getMenuCooks()->orderBy(['cook_id' => SORT_ASC])->all();
        $activeOrders = OrderAsset::find()->innerJoin('order', 'order_asset.order_id = order.id')->innerJoin('menu', 'order.menu_id = menu.id')->where(['menu.id' => $menu->id, 'order_asset.finished' => 0])->all();
        foreach ($menuCooks as $menuCook) {
            $cookAvailable = true;
            foreach ($activeOrders as $activeOrder) {
                if ($activeOrder->cook_id === $menuCook->cook_id) {
                    $cookAvailable = false;
                    break;
                }
            }
            if ($cookAvailable && ($orderTypeId === 1 || User::hasRole($menuCook->cook_id, 6))) {
                return User::findOne($menuCook->cook_id);
            }
        }
        return null;*/
    }

    public function actionListar()
    {
        try {
            return ['code' => 'success', 'msg' => 'Datos cargados.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }

    public function actionCrear()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $menu = MenuHelper::getCurrentMenu($this->requestParams['branch_id']);
            $tableNumber = isset($this->requestParams['item']['table_number']) ? $this->requestParams['item']['table_number'] : -1;
            $model = new Order(['date_time' => time(), 'status_id' => 0, 'menu_id' => $menu->id, 'order_number' => $this->_getNextOrderNumber($menu->id, $tableNumber)]);
            $this->_setModelAttributes($model);
            $this->_canWaiterCreateOrder($model);
            if (!$model->validate()) {
                return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($model), 'data' => $this->_getItems()];
            }
            $this->_updateOrderAssets($model);
            $this->_assignNextPendingOrders();
            $transaction->commit();
            return ['code' => 'success', 'msg' => 'Operación realizada.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            $transaction->rollBack();
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    private function _getNextOrderNumber($menuId, $tableNumber)
    {
        if ($tableNumber > 0) {
            $maxNumber = Order::find()->where(['menu_id' => $menuId, 'order_type_id' => 1, 'table_number' => $tableNumber])->max('[[order_number]]');
        } else {
            $maxNumber = Order::find()->where(['menu_id' => $menuId, 'order_type_id' => 2])->max('[[order_number]]');
        }
        return $maxNumber ? $maxNumber + 1 : 1;
    }

    public function actionEditar()
    {
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

    private function _assignNextPendingOrders()
    {
        $menu = MenuHelper::getCurrentMenu($this->requestParams['branch_id']);
        $menuId = $menu->id;
        $nextTakeAwayOrder = $this->_getNextPendingOrderByTypeId($menuId, 2);
        $nextRegularOrder = $this->_getNextPendingOrderByTypeId($menuId, 1);

        if ($nextTakeAwayOrder) {
            $this->_assignOrderAssetsToCookOrWaiter($nextTakeAwayOrder);
        }
        if ($nextRegularOrder) {
            $this->_assignOrderAssetsToCookOrWaiter($nextRegularOrder);
        }
    }

    private function _getNextPendingOrderByTypeId($menuId, $orderTypeId)
    {
        return Order::find()
            ->where(['menu_id' => $menuId, 'order_type_id' => $orderTypeId, 'status_id' => 0])
            ->orderBy(['date_time' => SORT_ASC])
            ->one();
    }

    private function _assignOrderAssetsToCookOrWaiter(Order &$model)
    {
        $orderAssets = $model->getOrderAssets()->where(['finished' => 0])->all();
        $assetsCount = count($orderAssets);
        if ($assetsCount > 0) {
            $newCook = $this->_getFirstAvailableCook($model->order_type_id);
            $newCookAssigned = false;
            $oldCooks = [];
            $waiterId = $orderAssets[0]->waiter_id;
            $assignedAssetsCount = 0;
            $assignedAssetsToWaiterCount = 0;
            foreach ($orderAssets as &$orderAsset) {
                if (!$orderAsset->cook_id) {
                    $asset = $orderAsset->getAsset()->one();
                    if ($this->_assetNeedsCooking($asset)) {
                        if ($newCook) {
                            $orderAsset->cook_id = $newCook->id;
                            $newCookAssigned = true;
                            $assignedAssetsCount++;
                        }
                    } else {
                        $orderAsset->cook_id = $waiterId;
                        $orderAsset->finished = 1;
                        $assignedAssetsToWaiterCount++;
                        $assignedAssetsCount++;
                    }
                    $orderAsset->save();
                } else {
                    $assignedAssetsCount++;
                    $oldCook = User::findOne($orderAsset->cook_id);
                    if (User::hasRole($oldCook->id, 4) || User::hasRole($oldCook->id, 6)) {
                        $oldCooks[$orderAsset->cook_id] = User::findOne($orderAsset->cook_id);
                    }
                }
            }

            $model->status_id = ($assignedAssetsToWaiterCount === $assetsCount ? 2 : ($assignedAssetsCount === $assetsCount ? 1 : 0));

            if ($model->save()) {
                if ($newCookAssigned) {
                    $this->_notifyWaiterAndMenuCooks($model, $waiterId, $newCook);
                }
                $this->_notifyOldCooks($model, $oldCooks, "modificada");
            }
        }
    }

    private function _notifyWaiterAndMenuCooks($model, $waiterId, $cook)
    {
        $orderTypeDesc = $model->order_type_id === 1 ? " de la mesa {$model->table_number}" : " para llevar";
        switch ($model->status_id) {
            case 0:
                $this->createNotification('Orden en cola', "La orden {$model->order_number}$orderTypeDesc se encuentra en cola.", date('Y-m-d h:i'), $waiterId, $model->id);
                break;
            case 1:
                if ($cook) {
                    $cookName = $cook->getFullName();
                    $cookGenderEnding = $cook->sex === 'M' ? 'o' : 'a';
                    $this->createNotification('Orden asignada', "{$cookName} fue asignad{$cookGenderEnding} a la orden {$model->order_number}$orderTypeDesc.", date('Y-m-d h:i'), $waiterId, $model->id);
                }
                break;
            case 2:
                $this->createNotification('Orden elaborada', "La orden {$model->order_number}$orderTypeDesc está lista.", date('Y-m-d h:i'), $waiterId, $model->id);
                break;
            default:
                break;
        }
    }

    private function _notifyOldCooks($model, $oldCooks, $action)
    {
        if (count($oldCooks) > 0) {
            $cooksFullNames = "";
            foreach ($oldCooks as $oldCook) {
                $cooksFullNames .= "{$oldCook->getFullName()}, ";
            }
            $cooksFullNames = rtrim($cooksFullNames, ', ');
            $menu = MenuHelper::getCurrentMenu($this->requestParams['branch_id']);
            $menuCooks = $menu->getCooks()->all();
            $orderTypeDesc = $model->order_type_id === 1 ? " de la mesa {$model->table_number}" : " para llevar";
            foreach ($menuCooks as $menuCook) {
                $this->createNotification("Order $action", "$cooksFullNames: la orden {$model->order_number}$orderTypeDesc fue $action.", date('Y-m-d h:i'), $menuCook->id, ($action !== "eliminada" ? $model->id : null));
            }
        }
    }

    public function actionElaborar()
    {
        try {
            $model = Order::find()->where(['id' => $this->requestParams['id']])->one();
            if (!$model) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $this->_serveOrderAssets($model, $this->requestParams['cook_id']);
            $this->_assignNextPendingOrders();
            return ['code' => 'success', 'msg' => 'Operación realizada.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionCerrar()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = Order::findOne(['id' => $this->requestParams['id'], 'status_id' => 2]);

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

    private function _reduceStockAssetsByOrder(Order $model)
    {
        $branch = $model->getMenu()->one()->getBranch()->one();
        $orderAssets = $model->getOrderAssets()->all();
        foreach ($orderAssets as $orderAsset) {
            $asset = $orderAsset->getAsset()->one();
            if ($this->_assetNeedsCooking($asset)) {
                $this->_reduceIngredients($branch, $asset, $orderAsset->quantity);
            } else {
                $stockEntry = $asset->getStocks()->orderBy(['id' => SORT_ASC])->one();
                $stockEntry->quantity -= $orderAsset->quantity;
                $stockEntry->save();
            }
        }
    }

    private function _reduceIngredients(Branch $branch, Asset $asset, $quantity)
    {
        $ingredients = AssetComponent::findAll(['asset_id' => $asset->id]);
        foreach ($ingredients as $ingredient) {
            $stockEntry = Stock::find()->where(['branch_id' => $branch->id, 'asset_id' => $ingredient->component_id])->orderBy(['id' => SORT_ASC])->one();

            $ingredientMeasureUnitId = $ingredient->measure_unit_id;
            $stockMeasureUnitId = $stockEntry->measure_unit_id;

            $amountToReduce = ($ingredientMeasureUnitId === $stockMeasureUnitId)
                ? $ingredient->quantity
                : $ingredient->quantity / 1000;


            $stockEntry->quantity -= ($amountToReduce * $quantity);
            $stockEntry->save();
        }
    }

    public function actionEliminar()
    {
        try {
            $model = Order::find()->where(['id' => $this->requestParams['id']])->andWhere('status_id in (0, 1)')->one();
            if (!$model) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $this->_notifyCooksOrderCancelled($model);
            $model->delete();
            $this->_assignNextPendingOrders();
            return ['code' => 'success', 'msg' => 'Operación realizada.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    private function _notifyCooksOrderCancelled(Order $model)
    {
        $orderAssets = $model->getOrderAssets()->where(['finished' => 0])->all();
        $oldCooks = [];
        foreach ($orderAssets as &$orderAsset) {
            if ($orderAsset->cook_id) {
                $oldCook = User::findOne($orderAsset->cook_id);
                if (User::hasRole($oldCook->id, 4) || User::hasRole($oldCook->id, 6)) {
                    $oldCooks[$orderAsset->cook_id] = User::findOne($orderAsset->cook_id);
                }
            }
        }
        $this->_notifyOldCooks($model, $oldCooks, "eliminada");
    }


    public function actionServirProductos()
    {
        try {
            $model = Order::find()->where(['id' => $this->requestParams['id']])->andWhere('status_id in (0, 1)')->one();
            if (!$model) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $this->_serveOrderAssets($model, $this->userInfo['user']->id);
            return ['code' => 'success', 'msg' => 'Operación realizada.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionVerPendientes()
    {
        try {
            return [
                'code' => 'success',
                'msg' => 'Datos cargados.',
                'data' => $this->_getItems(),
            ];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }

    private function _serveOrderAssets(Order $model, $cookId)
    {
        $orderAssets = $model->getOrderAssets()->where(['finished' => 0])->all();
        $i = 0;
        $waiterId = null;
        foreach ($orderAssets as $orderAsset) {
            if ($orderAsset->cook_id == $cookId) {
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
        $orderTypeDesc = $model->order_type_id === 1 ? " de la mesa {$model->table_number}" : " para llevar";
        $this->createNotification('Orden elaborada', "Un pedido de la orden {$model->order_number}$orderTypeDesc, elaborado por {$cook->getFullName()}, está listo.", date('Y-m-d h:i'), $waiterId, $model->id);
    }

    private function _getPendingOrders($menuId)
    {
        $orders = Order::find()->where(['menu_id' => $menuId, 'status_id' => 0])->orderBy(['date_time' => SORT_ASC])->all();
        $res = [];
        $i = 0;
        foreach ($orders as &$order) {
            $res[$i] = $order->getAttributes();
            $res[$i]['elapsed_time'] = Utilities::dateDiff($order->date_time, time());
            $res[$i]['order_type'] = OrderType::findOne($order->getOrderType()->one()->name);
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

    private function _getCurrentOrderForCooks(&$cooks, $menuId)
    {
        $i = 0;
        foreach ($cooks as &$cook) {
            $cookId = $cook['id'];
            $orderAssets = OrderAsset::find()
                ->innerJoin('asset', 'order_asset.asset_id = asset.id')
                ->where(['order_asset.cook_id' => $cookId, 'order_asset.finished' => 0])
                ->select(['order_asset.order_id', 'order_asset.quantity', 'asset.name as asset_name'])
                ->asArray()
                ->all();
            if ($orderAssets) {
                $order = Order::findOne($orderAssets[0]['order_id'])->getAttributes();
                $order['elapsed_time'] = Utilities::dateDiff($order['date_time'], time());
                $order['assets'] = $orderAssets;
            } else {
                $order = null;
            }
            $menuCook = MenuCook::findOne(['menu_id' => $menuId, 'cook_id' => $cookId]);
            $cooks[$i]['session_id'] = $menuCook ? $menuCook->session_id : null;
            $cooks[$i++]['current_order'] = $order;
        }
    }

    private function _canWaiterCreateOrder(Order $model)
    {
        if (!(($model->order_type_id === 2 && User::hasRole($this->userInfo['user']->id, 5)) || ($model->order_type_id === 1 && User::hasRole($this->userInfo['user']->id, 3)))) {
            throw new Exception("Usted no está autorizado a tomar este tipo de orden.");
        }
    }

}
