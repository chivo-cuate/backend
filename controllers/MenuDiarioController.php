<?php

namespace app\controllers;

use app\models\Asset;
use app\models\Menu;
use app\models\MenuAsset;
use app\utilities\Utilities;
use Exception;

class MenuDiarioController extends MyRestController {

    public $modelClass = Menu::class;
    
    private function _getCurrMenu() {
        return Menu::find()->where(['branch_id' => $this->requestParams['branch_id']])->orderBy(['date' => SORT_DESC])->one();
    }

    private function _getItems() {
        $model = $this->_getCurrMenu();
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
                ->where('stock.quantity > 0')
                ->asArray()
                ->all();
        return [$menuEntries, $activeAssets];
    }

    public function actionListar() {
        try {
            return ['code' => 'success', 'msg' => 'Datos cargados.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            return $exc->getMessage();
        }
    }

    public function actionCrear() {
        $newMenu = null;
        try {
            $oldMenu = $this->_getCurrMenu();
            $today = date('Y-m-d');
            
            if (!$oldMenu) {
                $oldMenu = new Menu(['date' => $today, 'branch_id' => $this->requestParams['branch_id']]);
                $oldMenu->save();
            }
            elseif ($oldMenu->date !== $today) {
                $newMenu = new Menu(['date' => $today, 'branch_id' => $this->requestParams['branch_id']]);
                $newMenu->save();
                $oldMenuAssets = $oldMenu->getMenuAssets()->all();
                foreach ($oldMenuAssets as $oldMenuAsset) {
                    $newMenuAsset = new MenuAsset($oldMenuAsset->getAttributes(['asset_id', 'price', 'grams']));
                    $newMenuAsset->menu_id = $newMenu->id;
                    $newMenuAsset->save();
                }
            }
            
            $menuAsset = new MenuAsset(['menu_id' => $oldMenu->id]);
            $this->_setModelAttributes($menuAsset);

            if ($menuAsset->validate()) {
                $menuAsset->save();
                return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getItems()];
            }
            return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($menuAsset), 'data' => []];
        } catch (Exception $exc) {
            if ($newMenu) {
                $newMenu->delete();
            }
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionEditar() {
        try {
            $item = MenuAsset::findOne($this->requestParams['item']['id']);
            if (!$item) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $this->_setModelAttributes($item);
            if ($item->validate()) {
                $item->save();
                return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getItems()];
            }
            return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($item), 'data' => []];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionEliminar() {
        try {
            $item = MenuAsset::findOne($this->requestParams['id']);
            $currMenu = $this->_getCurrMenu();
            if (!$item || $item->menu_id !== $currMenu->id) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            $item->delete();
            return ['code' => 'success', 'msg' => 'Elemento eliminado.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

}
