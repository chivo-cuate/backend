<?php

namespace app\controllers;

use app\models\Notification;
use app\models\User;
use app\utilities\Security;
use app\utilities\Utilities;
use Exception;
use Firebase\JWT\JWT;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class MyRestController extends ActiveController {

    private $key = "vUrQrZL50m7qL3uosytRJbeW8fzSwUqd";
    protected $userInfo = ['code' => null, 'msg' => null, 'user' => null];
    protected $request;
    protected $requestParams;
    public $enableCsrfValidation = false;

    public function beforeAction($action) {
        $this->request = Yii::$app->getRequest();
        $this->getUserInfo();
        $this->getRequestParams();

        Yii::$app->response->format = Response::FORMAT_JSON;
        $actionId = $action->controller->id . '/' . $action->id;
        date_default_timezone_set('America/Mexico_City');
        if ($action->controller->id !== 'auth') {
            return Security::verifyUserPermission($this->userInfo['user'], $actionId);
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        $behaviors = parent::behaviors();

        $auth = $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['dashboard'],
        ];
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        $access = $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                    [
                    'actions' => ['login'],
                    'allow' => true,
                ],
            ],
        ];

        unset($behaviors['authenticator']);
        unset($behaviors['access']);

        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
            'cors' => [
                // restrict access to
                'Access-Control-Allow-Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                // Allow only POST and PUT methods
                'Access-Control-Allow-Headers' => ['*'],
                // Allow only headers 'X-Wsse'
                'Access-Control-Allow-Credentials' => '*',
                // Allow OPTIONS caching
                'Access-Control-Max-Age' => 86400,
                // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                'Access-Control-Expose-Headers' => [],
            ]
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        $behaviors['access'] = $access;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }

    protected function _getNotifications() {
        $notifications = Notification::find()->where(['user_id' => $this->userInfo['user']->id])->orderBy(['created_at' => SORT_DESC])->asArray()->all();
        foreach ($notifications as &$notification) {
            $createdAt = intval($notification['created_at']);
            $notification['created_at'] = $notification['headline'];
            $notification['headline'] = Utilities::dateDiff($createdAt, time());
        }
        return $notifications;
    }

    protected function createNotification($title, $subtitle, $headline, $userId, $orderId) {
        Notification::deleteAll(['user_id' => $userId, 'order_id' => $orderId]);
        $model = new Notification([
            'order_id' => $orderId,
            'user_id' => $userId,
            'title' => $title,
            'subtitle' => $subtitle,
            'headline' => $headline,
            'status' => 1,
            'created_at' => time(),
        ]);
        if ($model->validate()) {
            $model->save();
        }
    }

    protected function _exitIfValidationFails(&$model) {
        if (!$model->validate()) {
            throw new Exception(Utilities::getModelErrorsString($model));
        }
    }

    protected function _setModelAttributes(ActiveRecord &$model) {
        $attributes = ($model->hasAttribute('branch_id') && isset($this->requestParams['branch_id'])) ? ['branch_id' => $this->requestParams['branch_id']] : [];
        foreach ($this->requestParams['item'] as $paramKey => $paramValue) {
            if ($model->hasAttribute($paramKey)) {
                $attributes[$paramKey] = $paramValue;
            }
        }
        $model->setAttributes($attributes);
    }

    public function encodeJWT($payload) {
        try {
            return JWT::encode($payload, $this->key);
        } catch (Exception $exc) {
            return null;
        }
    }

    public function decodeJWT($jwt) {
        try {
            $payload = JWT::decode($jwt, $this->key, ['HS256']);
            return ($payload->exp > time()) ? $payload : null;
        } catch (Exception $exc) {
            return null;
        }
    }

    public function checkAccess($action, $requiresAuth = true, $model = null) {
        if (!$this->userInfo['user'] === $requiresAuth) {
            throw new ForbiddenHttpException('Acceso denegado.');
        }
    }

    public function getRequestParams() {
        $this->requestParams = array_merge($this->request->get(), $this->request->post());
    }

    public function getUserInfo() {
        try {
            $jwt = str_replace('Bearer ', '', $this->request->getHeaders()->get('Authorization'));
            $payload = $this->decodeJWT($jwt);
            if ($payload->exp > time() && $payload->ip === $this->request->getUserIP()) {
                $user = User::findOne($payload->user_id);
                $this->userInfo = $user ? ['code' => 'success', 'msg' => 'User verified', 'user' => $user] : ['code' => 'error', 'msg' => 'Invalid credentials.', 'user' => null];
            }
        } catch (Exception $exc) {
            $this->userInfo = ['code' => 'error', 'msg' => $exc->getMessage(), 'user' => null];
        }
    }

}
