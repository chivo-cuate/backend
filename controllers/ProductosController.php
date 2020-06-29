<?php

namespace app\controllers;

class ProductosController extends AssetsController {

    public function beforeAction($action) {
        $this->assetTypeId = 2;
        return parent::beforeAction($action);
    }

}
