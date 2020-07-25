<?php

namespace app\controllers;

use app\models\Asset;
use app\models\AssetCategory;
use app\models\AssetComponent;
use app\models\MeasureUnitType;
use app\utilities\Utilities;
use DusanKasan\Knapsack\Collection;
use Exception;
use Yii;
use yii\db\IntegrityException;

class CategoriasController extends MyRestController
{

    protected $assetTypeId;
    public $modelClass = Asset::class;

    private function _findModel($id)
    {
        return AssetCategory::findOne($id);
    }

    private function _getItems()
    {
        $items = Collection::from(
            AssetCategory::find()
                ->innerJoin('measure_unit_type', 'asset_category.measure_unit_type_id = measure_unit_type.id')
                ->select('asset_category.id, asset_category.name, asset_category.needs_cooking, asset_category.measure_unit_type_id, measure_unit_type.name as measure_unit_type')
                ->orderBy(['asset_category.name' => SORT_ASC])
                ->asArray()
                ->all())
            ->map(function ($item) {
                $item['needs_cooking_desc'] = $item['needs_cooking'] ? 'Sí' : 'No';
                return $item;
            })
            ->toArray();

        $measureUnits = MeasureUnitType::find()->asArray()->all();

        return [$items, $measureUnits];
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
            $model = new AssetCategory();
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
                'needs_cooking' => $this->requestParams['item']['needs_cooking'],
                'measure_unit_type_id' => $this->requestParams['item']['measure_unit_type_id']
            ]);
            $model->validate();
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
            if (!$model->hasErrors()) {
                $model->delete();
                return ['code' => 'success', 'msg' => 'Operación realizada con éxito.', 'data' => $this->_getItems()];
            }
            return ['code' => 'error', 'msg' => Utilities::getModelErrorsString($model), 'data' => []];
        } catch (IntegrityException $exc) {
            return ['code' => 'error', 'msg' => 'Este elemento posee productos asociados.', 'data' => []];
        } catch (Exception $exc) {
            return ['code' => 'error', 'msg' => $exc->getMessage(), 'data' => []];
        }
    }

    public function actionEditarProductos()
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
