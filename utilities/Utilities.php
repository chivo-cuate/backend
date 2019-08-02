<?php

namespace app\utilities;

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

}
