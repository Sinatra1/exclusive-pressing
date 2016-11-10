<?php

use yii\db\Migration;
use app\models\User;

class m161110_115217_create_database_exclusivepressing extends Migration
{

    public function safeUp()
    {
        $this->createTable(User::tableName(), [
            'id' => $this->primaryKey(),
            'created' => $this->dateTime()->notNull()->defaultExpression('NOW()'),
            'updated' => $this->dateTime()->notNull()->defaultExpression('NOW()'),
            'deleted' => $this->dateTime()->null(),
            'login' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
            'birthdate' => $this->date()->notNull(),
            'options' => $this->text()->null(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
        
    }

    public function safeDown()
    {
        $this->dropTable(User::tableName());
    }

}
