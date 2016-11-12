<?php

namespace app\components\user;

use Yii;
use yii\web\ServerErrorHttpException;

class DeleteAction extends \yii\rest\DeleteAction
{
    /**
     * Deletes a model.
     * @param mixed $id id of the model to be deleted.
     * @throws ServerErrorHttpException on failure.
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        if ($model->markDeleted() === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }
        
        \Yii::$app->user->logout();

        \Yii::$app->getResponse()->setStatusCode(204);
    }
}
