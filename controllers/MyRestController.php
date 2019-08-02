<?php

namespace app\controllers;

use app\models\User;
use app\utilities\Security;
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
        //date_default_timezone_set('America/Mexico');
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
