<?php

namespace app\models;

use yii\db\ActiveRecord;

class Entry extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'entry';
    }
}

