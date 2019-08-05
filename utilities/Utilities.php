<?php

namespace app\utilities;

use app\models\Menu;
use yii\db\ActiveRecord;

class Utilities {

    public static function getModelErrorsString(ActiveRecord $model) {
        $errors = [];
        foreach ($model->getErrors() as $error) {
            if (!self::arrayContains($errors, $error[0])) {
                $errors[] = $error[0];
            }
        }
        return implode('. ', $errors);
    }

    public static function arrayContains($array, $string) {
        foreach ($array as $value) {
            if ($value === $string) {
                return true;
            }
        }
        return false;
    }
    
    public static function getCurrentMenu($branchId) {
        return Menu::find()->where(['branch_id' => $branchId])->orderBy(['date' => SORT_DESC])->one();
    }

}
