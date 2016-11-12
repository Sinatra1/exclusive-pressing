<?php

namespace app\components\auth;

use yii\rest\Action;

class LogoutAction extends Action
{

    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }
        
        \Yii::$app->user->logout();
        
        return [true];
    }

}
