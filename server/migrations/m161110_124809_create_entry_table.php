<?php

use yii\db\Migration;
use app\models\Entry;
use app\models\User;

/**
 * Handles the creation of table `entry`.
 */
class m161110_124809_create_entry_table extends Migration
{

    public function safeUp()
    {
        $this->createTable(Entry::tableName(), [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'created' => $this->dateTime()->notNull()->defaultExpression('NOW()'),
            'ip' => $this->string(35)->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->addForeignKey(
            User::tableName() . '_' . Entry::tableName() . '_' . '_fk', 
            Entry::tableName(), 
            'user_id', 
            User::tableName(), 
            'id'
        );
        
        $this->createIndex('idx_' . Entry::tableName() . '_user_id', Entry::tableName(), 'user_id');
    }

    public function safeDown()
    {
        $this->dropTable(Entry::tableName());
    }

}
