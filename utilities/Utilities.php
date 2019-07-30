<?php

namespace app\utilities;

use yii\db\ActiveRecord;

class Utilities {

    public static function getModelErrorsString(ActiveRecord $model) {
        $errors = '';
        foreach ($model->getErrors() as $key => $value) {
            $errors .= ($value[0] . ' ');
        }
        return $errors;
    }

}
