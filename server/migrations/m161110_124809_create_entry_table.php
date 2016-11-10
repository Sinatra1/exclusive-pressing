<?php

use yii\db\Migration;
use app\models\Entry;

/**
 * Handles the creation of table `entry`.
 */
class m161110_124809_create_entry_table extends Migration
{
    public function safeUp()
    {
        $this->createTable(Entry::tableName(), [
            'id' => $this->primaryKey(),
            'created' => $this->dateTime()->notNull()->defaultExpression('NOW()'),
            'ip' => $this->string(35)->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=MyISAM');
        
    }

    public function safeDown()
    {
        $this->dropTable(Entry::tableName());
    }
}
