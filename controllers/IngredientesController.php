<?php

namespace app\controllers;

class IngredientesController extends AssetsController {

    public function beforeAction($action) {
        $this->assetTypeId = 1;
        return parent::beforeAction($action);
    }

}
