<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;

class User extends ActiveRecord implements IdentityInterface
{

    public $authKey;
    public $accessToken;

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'password', 'birthdate'], 'required'],
            [['login'], 'string'],
            [['login', 'password'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password' => 'Password',
            'birthdate' => 'Birthdate',
        ];
    }
    
    /**
     * @return array
     */
    public function fields()
    {
        $fields = parent::fields();

        unset($fields['password']);
        unset($fields['access_token']);

        return $fields;
    }

    public function beforeSave($insert)
    {
        $result = parent::beforeSave($insert);

        if (!empty($this->password)) {
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        }

        if ($this->isNewRecord) {
            $this->access_token = Yii::$app->getSecurity()->generatePasswordHash(rand(0, 1000));
        }

        return $result;
    }
    
    public static function findIdentityByLoginAndPassword($login, $password)
    {
        $password = Yii::$app->getSecurity()->generatePasswordHash($password);
        
        return static::findOne(['login' => $login, 'password' => $password]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
