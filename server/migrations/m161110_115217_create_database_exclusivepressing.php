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
            'is_deleted' => $this->boolean()->notNull()->defaultValue('0'),
            'login' => $this->string()->unique()->notNull(),
            'birthdate' => $this->date()->notNull(),
            'options' => $this->text()->null(),
            'password' => $this->text()->notNull(),
            'access_token' => $this->text()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
          
        $this->createIndex('idx_' . User::tableName() . '_login', User::tableName(), 'login');
    }

    public function safeDown()
    {
        $this->dropTable(User::tableName());
    }

}
