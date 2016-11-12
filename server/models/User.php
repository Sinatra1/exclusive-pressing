<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\models\Entry;
use Yii;

class User extends ActiveRecord implements IdentityInterface
{

    public $authKey;
    public $accessToken;
    protected $cookieTime = 2592000;

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntries()
    {
        return $this->hasMany(Entry::className(), ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'password', 'birthdate'], 'required'],
            [['login', 'options'], 'string'],
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

        $userArray = User::find()->where(['login' => $this->login])->asArray()->one();

        if (!empty($this->password) && $this->password != $userArray['password']) {
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        }

        if ($this->isNewRecord) {
            $this->access_token = Yii::$app->getSecurity()->generatePasswordHash(rand(0, 1000));
        }

        return $result;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'is_deleted' => false]);
    }

    public function markDeleted()
    {
        $this->login .= md5(rand(0, 1000));
        $this->is_deleted = true;
        $this->deleted = date('Y-m-d H:i:s', time());
        $result = $this->save();

        return $result;
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

    public function login()
    {
        $result = \Yii::$app->user->login($this, $this->cookieTime);

        if (empty($result)) {
            return $result;
        }

        $entry = new Entry(['user_id' => \Yii::$app->user->id, 'ip' => \Yii::$app->request->getUserIP()]);
        $entry->save();

        return $result;
    }

}
