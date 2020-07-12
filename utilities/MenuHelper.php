<?php

namespace app\utilities;

use app\models\Menu;
use app\models\MenuCook;

class MenuHelper {

    public static function getCurrentMenu($branchId) : Menu {
        return Menu::find()->where(['branch_id' => $branchId])->orderBy(['date' => SORT_DESC])->one();
    }

    public static function getCurrentMenuCooks($branchId) {
        $currMenu = self::getCurrentMenu($branchId);

        return $currMenu
            ->getMenuCooks()
            ->innerJoin('auth_user', 'menu_cook.cook_id = auth_user.id')
            ->select(['auth_user.id', 'username', 'session_id', 'concat(first_name, " ", last_name) as full_name, auth_user.sex'])
            ->asArray()
            ->all();
    }

}
