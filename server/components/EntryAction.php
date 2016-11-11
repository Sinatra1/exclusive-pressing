<?php

namespace app\components;

use yii\rest\Action;

class EntryAction extends Action
{

    public function run($id)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }
        
        /* @var $model ActiveRecord */
        $user = $this->findModel($id);
        
        return $user->entries;
    }

}
