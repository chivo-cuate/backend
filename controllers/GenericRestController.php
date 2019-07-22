<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\rest\Controller;
use yii\web\Response;

class GenericRestController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            throw new \Exception('oh, shit');
                        }
                    ],
                ],
            ]
        ];
    }

}
