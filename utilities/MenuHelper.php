<?php

namespace app\utilities;

use app\models\Menu;

class MenuHelper {

    public static function getCurrentMenu($branchId) {
        return Menu::find()->where(['branch_id' => $branchId])->orderBy(['date' => SORT_DESC])->one();
    }

}
