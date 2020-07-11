<?php

namespace app\controllers;

use app\models\Asset;
use app\models\AssetCategory;
use app\models\AssetComponent;
use app\models\MeasureUnit;
use app\models\MenuAsset;
use app\models\OrderAsset;
use app\models\Stock;
use app\utilities\MenuHelper;
use app\utilities\Utilities;
use Exception;
use Yii;

class AssetsController extends MyRestController
{

    protected $assetTypeId;
    public $modelClass = Asset::class;

    private function _findModel($id)
    {
        return Asset::findOne([
            'id' => $id,
            'asset_type_id' => $this->assetTypeId,
        ]);
    }

    private function _getItemComponents(&$asset)
    {
        return AssetComponent::find()->where(['asset_component.asset_id' => $asset['id']])->innerJoin('asset', 'asset.id = asset_component.component_id')->innerJoin('measure_unit', 'measure_unit.id = asset_component.measure_unit_id')->select(['asset_component.*', 'asset.name', 'measure_unit.name as measure_unit_name'])->asArray()->all();
    }

    private function _getAssetsByType($typeId, $active)
    {
        $params = ['asset_type_id' => $typeId];
        if ($active) {
            $params['status'] = 1;
        }
        return Asset::find()->where($params)
            ->orderBy(['category_id' => SORT_ASC, 'name' => SORT_ASC])
            ->asArray()
            ->all();
    }

    private function _getMeasureUnits()
    {
        return MeasureUnit::find()->asArray()->all();
    }

    private function _getAssetsCategories()
    {
        return $this->assetTypeId === 2 ? AssetCategory::find()->asArray()->all() : [];
    }

    private function _getItems()
    {
        $items = $this->_getAssetsByType($this->assetTypeId, false);
        foreach ($items as &$item) {
            $category = AssetCategory::findOne($item['category_id']);
            $item['status'] = $item['status'] === '1' ? true : false;
            $item['status_name'] = $item['status'] ? 'Activo' : 'Inactivo';

            if ($category) {
                $item['category_name'] = $category->name;
                $item['category_needs_cooking'] = $category->needs_cooking === 1;
            } else {
                $item['category_name'] = 'Ingrediente';
                $item['category_needs_cooking'] = false;
            }

            if ($this->assetTypeId === 2)
                $item['components'] = $this->_getItemComponents($item);
        }
        $ingredients = $this->assetTypeId === 1 ? $items : $this->_getAssetsByType(1, true);
        return [$items, $ingredients, $this->_getMeasureUnits(), $this->_getAssetsCategories()];
    }

    private function _activeInStock(Asset &$model)
    {
        $item = Stock::find()->where(['asset_id' => $model->id])->andWhere('quantity > 0')->one();
        if ($item) {
            $model->addError('id', 'Aun existen ' . $item->quantity . ' ' . $item->getMeasureUnit()->one()->name . ' este producto en el almacén.');
        }
    }

    private function _activeInOrders(Asset &$model)
    {
        $item = OrderAsset::findOne(['asset_id' => $model->id]);
        if ($item) {
            $model->addError('id', 'Existen órdenes con este producto.');
        }
    }

    private function _activeInMenu(Asset &$model, $lookForCurrentMenu)
    {
        $params = ['asset_id' => $model->id];
        if ($lookForCurrentMenu) {
            $currentMenu = MenuHelper::getCurrentMenu($this->requestParams['branch_id']);
            if ($currentMenu) {
                $params['menu_id'] = $currentMenu->id;
            }
        }
        $item = MenuAsset::findOne($params);
        if ($item) {
            $model->addError('id', $lookForCurrentMenu ? 'Este producto se encuentra en el menú actual.' : 'Este producto fue incluido en el menú del ' . $item->getMenu()->one()->date);
        }
    }

    private function _canBeDisabled(Asset &$model)
    {
        $model->validate();
        if ($model->status === 0) {
            $this->_activeInStock($model);
            $this->_activeInMenu($model, true);
        }
    }

    private function _canBeDeleted(Asset &$model)
    {
        $model->validate();
        $this->_activeInStock($model);
        $this->_activeInMenu($model, false);
        $this->_activeInOrders($model);
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
        try {
            $model = new Asset(['status' => 1, 'asset_type_id' => $this->assetTypeId]);
            $this->_setModelAttributes($model);
            if ($model->validate()) {
                $model->save();
                return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getItems()];
            }
            return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($model), 'data' => []];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionEditar()
    {
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

    public function actionEliminar()
    {
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

    public function actionEditarIngredientes()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = $this->_findModel($this->requestParams['id']);
            if (!$model) {
                return ['code' => 'error', 'msg' => 'Datos incorrectos.', 'data' => []];
            }
            AssetComponent::deleteAll(['asset_id' => $model->id]);
            foreach ($this->requestParams['ingredients'] as $arrIngredient) {
                $newAssetComp = new AssetComponent(['asset_id' => $model->id, 'component_id' => $arrIngredient['component_id'], 'quantity' => $arrIngredient['quantity'], 'measure_unit_id' => $arrIngredient['measure_unit_id']]);
                $newAssetComp->save();
            }
            $transaction->commit();
            return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getItems()];
        } catch (Exception $exc) {
            $transaction->rollBack();
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

}
