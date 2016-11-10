<?php

namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBasicAuth;

class UserController extends ActiveController
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

        /*$behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
        ];*/

        return $behaviors;
    }

    /* public function checkAccess($action, $model = null, $params = [])
      {
      if ($action === 'update' || $action === 'delete') {
      if ($model->id !== \Yii::$app->user->id)
      throw new \yii\web\ForbiddenHttpException(sprintf('You can edit only yourself.', $action));
      }
      } */
}
