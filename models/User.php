<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "User".
 *
 * @property string $Username
 * @property string $Email
 * @property string $Password
 * @property string $AuthKey
 * @property string $AccessToken
 * @property int $Role
 * @property int $Id
 *
 * @property Commentaries[] $commentaries
 * @property Likes[] $likes
 * @property Video[] $videos
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $ConfirmPassword;

    public function getIsAdmin()
    {
        return $this->Role === 1;
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $cond = ['Id' => $id];
        return self::findSelf($cond);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $cond = ['AccessToken' => $token];
        return self::findSelf($cond);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $cond = ['Email' => $username];
        return self::findSelf($cond);
    }
    public static function findSelf($cond)
    {
        $user = self::findOne($cond);
        return ($user !== null) ? $user->toLogin() : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->Id;
    }

    public function getPassword()
    {
        return $this->Password;
    }

    public function setPassword($value)
    {
        $this->Password = $value;
    }
    public function getUsername()
    {
        return $this->Username;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->AuthKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->AuthKey === $authKey;
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

    public function toLogin()
    {
        return new static($this);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Email', 'Password', 'ConfirmPassword', 'Username'], 'required', 'message' => 'Данное поле обязательно для заполнения'],
            [['Email', 'Password', 'AuthKey', 'AccessToken'], 'string'],
            [['Role'], 'integer'],
            [['AuthKey', 'AccessToken'], 'default', 'value' => ""],
            [['Email'], 'email'],
            [['Email'], 'unique', 'message' => 'Пользователь с такой электронной почтой уже существует'],
            [['Username'], 'unique', 'message' => 'Пользователь с таким ником уже существует'],
            [['ConfirmPassword'], 'compare', 'compareAttribute' => 'Password', 'message' => "Пароли должны совпадать"]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Username' => 'Никнейм',
            'Email' => 'Электронная почта',
            'Password' => 'Пароль',
            'ConfirmPassword' => 'Подтверждение пароля',
        ];
    }

    /**
     * Gets query for [[Commentaries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommentaries()
    {
        return $this->hasMany(Commentaries::className(), ['UserId' => 'Id']);
    }

    /**
     * Gets query for [[Likes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLikes()
    {
        return $this->hasMany(Likes::className(), ['UserId' => 'Id']);
    }

    /**
     * Gets query for [[Videos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVideos()
    {
        return $this->hasMany(Video::className(), ['UserId' => 'Id']);
    }
}
