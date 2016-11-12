<?php

namespace app\components\user;

use yii\web\ServerErrorHttpException;
use yii\rest\Action;
use yii\base\Model;
use app\models\User;
use yii\helpers\Url;

class RegAction extends Action
{

    public $scenario = Model::SCENARIO_DEFAULT;
   
    public $viewAction = 'view';

    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $model = new $this->modelClass([
            'scenario' => $this->scenario,
        ]);
        
        $model->load(\Yii::$app->getRequest()->getBodyParams(), '');
        
        if ($model->save()) {
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute([$this->viewAction, 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        if (!$model->login()) {
            throw new ServerErrorHttpException('something is wrong inside Yii::$app->user->login');
        }
        
        $userArray = User::find()->where(['login' => $model->login])->asArray()->one();
        
        return ['accessToken' => $userArray['access_token'], 'id' => \Yii::$app->user->id];
    }
}
