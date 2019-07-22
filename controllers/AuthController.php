<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\filters\auth\HttpBearerAuth;
use yii\web\IdentityInterface;
use app\models\User;

class AuthController extends Controller/* implements IdentityInterface*/
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        return $behaviors;
    }

    public function beforeAction($action) {
        $actionId = $action->id;
        
        switch ($actionId) {
            case 'login':
            case 'test':
                $requiresAuth = false;
                break;
            default:
                $requiresAuth = true;
                break;
        }
        $this->checkAccess($actionId, $requiresAuth);
        return true;
    }
    
    public function actionTest() {
        return 123;
    }

    public function actionLogin() {
        $token = 't1';
        return User::findIdentityByAccessToken($token);
    }

    public function checkAccess($action, $requiresAuth = true, $model = null, $params = []) {
        if (Yii::$app->user->isGuest === $requiresAuth)
            throw new ForbiddenHttpException('Acceso denegado');
    }

    /*public static function findIdentityByAccessToken($token, $type = null) {
        return new User(['username' => 'pepe']);
    }

    public static function findIdentity($id) {
        return new User(['username' => 'pepe']);
    }

    public function getId() {
        return 1;
    }

    public function getAuthKey() {
        return '34rpij';
    }

    public function validateAuthKey($authKey) {
        return true;
    }*/

}
