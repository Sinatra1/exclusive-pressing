<?php

namespace app\controllers;

use yii\rest\ActiveController;

class AuthController extends ActiveController
{
    /*public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }*/
    
    public $modelClass = 'app\models\User';
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        /*$behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
        ];*/

        return $behaviors;
    }
    
    public function actions()
    {
        $actions = parent::actions();
        
        $actions['create'] = [
                'class' => 'app\components\AuthAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario,
            ];
        
        return $actions;
    }

}
