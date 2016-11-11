<?php

namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;

class AuthController extends ActiveController
{

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

        /* $behaviors['access'] = [
          'class' => AccessControl::className(),
          'rules' => [
          'allow' => true,
          'roles' => ['@'],
          ]
          ]; */

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        $actions['create'] = [
            'class' => 'app\components\LoginAction',
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
            'scenario' => $this->createScenario,
        ];

        $actions['index'] = [
            'class' => 'app\components\LogoutAction',
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
        ];

        return $actions;
    }

}
