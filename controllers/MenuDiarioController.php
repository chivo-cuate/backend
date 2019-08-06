<?php

namespace app\controllers;

use app\models\Asset;
use app\models\Menu;
use app\models\MenuAsset;
use app\utilities\Utilities;
use Exception;

class MenuDiarioController extends MyRestController {

    public $modelClass = Menu::class;

    private function _getItems() {
        $model = Utilities::getCurrentMenu($this->requestParams['branch_id']);
        $menuEntries = [];
        if ($model) {
            $menuEntries = $model->getMenuAssets()->asArray()->all();
            foreach ($menuEntries as &$menuEntry) {
                $asset = Asset::findOne($menuEntry['asset_id']);
                $menuEntry['date'] = $model->date;
                $menuEntry['asset_name'] = $asset->name;
            }
        }
        $activeAssets = Asset::find()
                ->select(['asset.id', 'asset.name', 'stock.price_in'])
                ->innerJoin('stock', 'stock.asset_id = asset.id')
                ->where(['asset.asset_type_id' => 2])
                ->andWhere(['asset.status' => 1])
                ->andWhere('stock.quantity > 0')
                ->asArray()
                ->all();
        return [$menuEntries, $activeAssets];
    }

    private function _updateMenu() {
        $newMenu = null;
        $today = date('Y-m-d');
        $currMenu = Utilities::getCurrentMenu($this->requestParams['branch_id']);

        if (!$currMenu) {
            $currMenu = new Menu(['date' => $today, 'branch_id' => $this->requestParams['branch_id']]);
            $currMenu->save();
        } elseif ($currMenu->date !== $today) {
            $newMenu = new Menu(['date' => $today, 'branch_id' => $this->requestParams['branch_id']]);
            $newMenu->save();
            $oldMenuAssets = $currMenu->getMenuAssets()->all();
            foreach ($oldMenuAssets as $oldMenuAsset) {
                $newMenuAsset = new MenuAsset($oldMenuAsset->getAttributes(['asset_id', 'price', 'grams']));
                $newMenuAsset->menu_id = $newMenu->id;
                $newMenuAsset->save();
            }
        }
        return [$currMenu, $newMenu];
    }

    private function _returnException(Menu $menu, Exception $exc) {
        if ($menu) {
            $menu->delete();
        }
        return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
    }

    public function actionListar() {
        try {
            return ['code' => 'success', 'msg' => 'Datos cargados.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }

    public function actionCrear() {
        $menues = [null, null];
        try {
            $menues = $this->_updateMenu();
            $menuAsset = new MenuAsset(['menu_id' => $menues[1] ? $menues[1]->id : $menues[0]->id]);
            $this->_setModelAttributes($menuAsset);

            if ($menuAsset->validate()) {
                $menuAsset->save();
                return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getItems()];
            }
            return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($menuAsset), 'data' => []];
        } catch (Exception $exc) {
            return $this->_returnException($menues[1], $exc);
        }
    }

    public function actionEditar() {
        $menues = [null, null];
        try {
            $menues = $this->_updateMenu();
            $menuAsset = MenuAsset::findOne($this->requestParams['item']['id']);

            if ($menues[1]) {
                $menuAsset = MenuAsset::findOne(['menu_id' => $menues[1]->id, 'asset_id' => $menuAsset->asset_id]);
                $this->_setModelAttributes($menuAsset);
                $menuAsset->menu_id = $menues[1]->id;
            } else {
                $this->_setModelAttributes($menuAsset);
            }

            if (!$menuAsset) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            } elseif ($menuAsset->validate()) {
                $menuAsset->save();
                return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getItems()];
            }
            return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($menuAsset), 'data' => []];
        } catch (Exception $exc) {
            return $this->_returnException($menues[1], $exc);
        }
    }

    public function actionEliminar() {
        $menues = [null, null];
        try {
            $menues = $this->_updateMenu();
            $menuAsset = MenuAsset::findOne($this->requestParams['id']);
            if ($menues[1]) {
                $menuAsset = MenuAsset::findOne(['menu_id' => $menues[1]->id, 'asset_id' => $menuAsset->asset_id]);
            }
            if ($menuAsset) {
                $menuAsset->delete();
                return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getItems()];
            }
            return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
        } catch (Exception $exc) {
            return $this->_returnException($menues[1], $exc);
        }
    }

}
