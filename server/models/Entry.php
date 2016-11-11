<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\User;

class Entry extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'entry';
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}

