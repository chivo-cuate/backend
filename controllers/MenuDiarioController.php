<?php

namespace app\controllers;

use app\models\Asset;
use app\models\AssetCategory;
use app\models\Menu;
use app\models\MenuAsset;
use app\models\MenuCook;
use app\models\ProductIngredient;
use app\models\User;
use app\utilities\MenuHelper;
use app\utilities\Utilities;
use Exception;
use Yii;

class MenuDiarioController extends MyRestController {

    public $modelClass = Menu::class;

    private function _getCurrMenuAssets($model) {
        $menuAssets = [];
        if ($model) {
            $menuAssets = $model->getMenuAssets()->asArray()->all();
            foreach ($menuAssets as &$menuAsset) {
                $asset = Asset::findOne($menuAsset['asset_id']);
                $menuAsset['date'] = $model->date;
                $menuAsset['asset_name'] = $asset->name;
            }
        }
        return $menuAssets;
    }

    private function _getAllCooksFromBranch() {
        $cooks = User::find()
                        ->innerJoin('branch_user', 'branch_user.user_id = auth_user.id')
                        ->innerJoin('auth_user_role', 'auth_user_role.user_id = auth_user.id')
                        ->innerJoin('auth_permission_role', 'auth_permission_role.role_id = auth_user_role.role_id')
                        ->where(['auth_permission_role.perm_id' => 34, 'branch_user.branch_id' => $this->requestParams['branch_id']])
                        ->select(['auth_user.id', 'concat(auth_user.first_name, " ", auth_user.last_name) as full_name'])
                        ->orderBy(['auth_user.first_name' => SORT_ASC, 'auth_user.last_name' => SORT_ASC, 'auth_user.username' => SORT_ASC])
                        ->asArray()->all();
        return $cooks;
    }

    private function _getCurrMenuCooks($model) {
        $menuCooks = [];
        if ($model) {
            $menuCooks = $model->getCooks()->asArray()->all();
            foreach ($menuCooks as &$menuCook) {
                $menuCook['full_name'] = "{$menuCook['first_name']} {$menuCook['last_name']}";
            }
        }
        return $menuCooks;
    }

    private function _sumAssetComponentsPrice($assetId) {
        $prodIngredients = ProductIngredient::find()->where(['asset_id' => $assetId])->all();
        $calculatedPrice = 0;
        $available = true;
        foreach ($prodIngredients as $prodIngredient) {
            if ($prodIngredient->units_left < 1) {
                $available = false;
                break;
            }
            $calculatedPrice += ($prodIngredient->required_quantity * $prodIngredient->price_in);
        }
        return [$available, ceil($calculatedPrice)];
    }

    private function _addAssetFromStockIfExists($categoryName, $assetsByCategory, &$res) {
        $res[]['header'] = $categoryName;
        foreach ($assetsByCategory as &$activeAsset) {
            $available = false;
            if ($activeAsset['quantity'] > 0) {
                $available = true;
            } else {
                $componentsInfo = $this->_sumAssetComponentsPrice($activeAsset['id']);
                $available = $componentsInfo[0];
                $activeAsset['price_in'] = $componentsInfo[1];
            }
            if ($available) {
                $res[] = [
                    'id' => $activeAsset['id'],
                    'name' => $activeAsset['name'],
                    'price_in' => $activeAsset['price_in'],
                    'group' => $categoryName,
                ];
            }
        }
        $res[]['divider'] = true;
    }

    private function _getActiveAssetsFromStock() {
        $assetCategories = AssetCategory::find()->orderBy(['name' => SORT_ASC])->all();
        $res = [];
        foreach ($assetCategories as $assetCategory) {
            $assetsByCategory = Asset::find()->select(['asset.id', 'asset.name', 'stock.price_in', 'stock.quantity'])->leftJoin('stock', 'stock.asset_id = asset.id')->where(['asset.branch_id' => $this->requestParams['branch_id'], 'asset.status' => 1, 'asset.asset_type_id' => 2, 'asset.category_id' => $assetCategory->id])->asArray()->all();
            $this->_addAssetFromStockIfExists($assetCategory->name, $assetsByCategory, $res);
        }
        if (count($res) > 0) {
            unset($res[count($res) - 1]);
        }
        return $res;
    }

    private function _getItems() {
        $model = MenuHelper::getCurrentMenu($this->requestParams['branch_id']);
        $menuAssets = $this->_getCurrMenuAssets($model);
        $activeAssets = $this->_getActiveAssetsFromStock();
        $menuCooks = $this->_getCurrMenuCooks($model);
        $allCooks = $this->_getAllCooksFromBranch();
        return [$menuAssets, $activeAssets, $menuCooks, $allCooks];
    }

    private function _copyMenuAssets(Menu $oldMenu, Menu $newMenu) {
        $oldMenuAssets = $oldMenu->getMenuAssets()->all();
        foreach ($oldMenuAssets as $oldMenuAsset) {
            $newMenuAsset = new MenuAsset(['menu_id' => $newMenu->id, 'asset_id' => $oldMenuAsset->asset_id, 'price' => $oldMenuAsset->price, 'grams' => $oldMenuAsset->grams]);
            $newMenuAsset->save();
        }
    }

    private function _copyMenuCooks(Menu $oldMenu, Menu $newMenu) {
        $oldMenuCooks = $oldMenu->getCooks()->all();
        foreach ($oldMenuCooks as $oldMenuCook) {
            $newMenuCook = new MenuCook(['menu_id' => $newMenu->id, 'cook_id' => $oldMenuCook->id]);
            $newMenuCook->save();
        }
    }

    private function _updateMenuCooks($menuId) {
        MenuCook::deleteAll(['menu_id' => $menuId]);
        $cooksForOrdersToCarryHomeCount = 0;
        foreach ($this->requestParams['cooks'] as $cook) {
            $user = User::findByIdPermAndBranch($cook, 34, $this->requestParams['branch_id']);
            if ($user) {
                $menuCook = new MenuCook(['menu_id' => $menuId, 'cook_id' => $user->id]);
                $menuCook->save();
                $cooksForOrdersToCarryHomeCount += (User::hasRole($user->id, 6) ? 1 : 0);
            }
        }
        if ($cooksForOrdersToCarryHomeCount === 0) {
            throw new \Exception("No hay elaboradores de órdenes para llevar");
        }
    }

    private function _updateMenu() {
        $newMenu = null;
        $today = date('Y-m-d');
        $oldMenu = MenuHelper::getCurrentMenu($this->requestParams['branch_id']);
        if (!$oldMenu) {
            $oldMenu = new Menu(['date' => $today, 'branch_id' => $this->requestParams['branch_id']]);
            $oldMenu->save();
        } elseif ($oldMenu->date !== $today) {
            $newMenu = new Menu(['date' => $today, 'branch_id' => $this->requestParams['branch_id']]);
            $newMenu->save();
            $this->_copyMenuAssets($oldMenu, $newMenu);
            $this->_copyMenuCooks($oldMenu, $newMenu);
        }
        return [$oldMenu, $newMenu];
    }

    private function _rollbackIfInvalidMenuAsset($menuAsset, $transaction) {
        if (!$menuAsset->validate()) {
            $transaction->rollBack();
            return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($menuAsset), 'data' => $this->_getItems()];
        }
    }

    private function _rollbackIfNullMenuAsset($menuAsset, $transaction) {
        if (!$menuAsset) {
            $transaction->rollBack();
            return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => $this->_getItems()];
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
            $menues = $this->_updateMenu();
            $menuAsset = new MenuAsset(['menu_id' => $menues[1] ? $menues[1]->id : $menues[0]->id]);
            $this->_setModelAttributes($menuAsset);
            $this->_rollbackIfInvalidMenuAsset($menuAsset, $transaction);
            $menuAsset->save();
            $transaction->commit();
            return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            $transaction->rollBack();
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => $this->_getItems()];
        }
    }

    public function actionEditar() {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $menues = $this->_updateMenu();
            $menuAsset = MenuAsset::findOne($this->requestParams['item']['id']);
            $this->_rollbackIfNullMenuAsset($menuAsset, $transaction);
            if ($menues[1]) {
                $menuAsset = MenuAsset::findOne(['menu_id' => $menues[1]->id, 'asset_id' => $menuAsset->asset_id]);
                $this->_setModelAttributes($menuAsset);
                $menuAsset->menu_id = $menues[1]->id;
            } else {
                $this->_setModelAttributes($menuAsset);
            }
            $this->_rollbackIfInvalidMenuAsset($menuAsset, $transaction);
            $menuAsset->save();
            $transaction->commit();
            return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            $transaction->rollBack();
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => $this->_getItems()];
        }
    }

    public function actionEliminar() {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $menues = $this->_updateMenu();
            $menuAsset = MenuAsset::findOne($this->requestParams['id']);
            if ($menues[1]) {
                $menuAsset = MenuAsset::findOne(['menu_id' => $menues[1]->id, 'asset_id' => $menuAsset->asset_id]);
            }
            $this->_rollbackIfNullMenuAsset($menuAsset, $transaction);
            $menuAsset->delete();
            $transaction->commit();
            return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            $transaction->rollBack();
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => $this->_getItems()];
        }
    }

    public function actionHabilitarElaboradores() {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = MenuHelper::getCurrentMenu($this->requestParams['branch_id']);
            if (!$model) {
                return ['code' => 'error', 'msg' => 'Aun no ha creado ningún menú.', 'data' => $this->_getItems()];
            }
            $this->_updateMenuCooks($model->id);
            $transaction->commit();
            return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            $transaction->rollBack();
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => $this->_getItems()];
        }
    }

}
