<?php

namespace app\components\auth;

use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;
use yii\rest\Action;
use yii\base\Model;
use app\models\User;
/**
 * CreateAction implements the API endpoint for creating a new model from the given data.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class LoginAction extends Action
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;
    /**
     * @var string the name of the view action. This property is need to create the URL when the model is successfully created.
     */
    public $viewAction = 'view';


    /**
     * Creates a new model.
     * @return \yii\db\ActiveRecordInterface the model newly created
     * @throws ServerErrorHttpException if there is any error when creating the model
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /* @var $model \yii\db\ActiveRecord */
        $model = new $this->modelClass([
            'scenario' => $this->scenario,
        ]);
        
        $model->load(\Yii::$app->getRequest()->getBodyParams(), '');
        
        $userArray = User::find()->where(['login' => $model->login])->asArray()->one();
        $user = User::findOne(['login' => $model->login]);
        
        if (empty($user)) {
            throw new BadRequestHttpException(json_encode(['login' => 'not_found']));
        }

        if (!\Yii::$app->getSecurity()->validatePassword($model->password, $userArray['password'])) {
            throw new ForbiddenHttpException(json_encode(['password' => 'is_wrong']));
        }

        if (!$user->login()) {
            throw new ServerErrorHttpException('something is wrong inside Yii::$app->user->login');
        }
        
        return ['accessToken' => $userArray['access_token'], 'id' => \Yii::$app->user->id];
    }
}
