<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'users';
    }

    public function newUser($login, $email, $password)
    {
        $this->login = $login;
        $this->email = $email;
        $this->password = \Yii::$app->security->generatePasswordHash($password);
        $this->code = \Yii::$app->security->generateRandomString();
        $this->access_token = \Yii::$app->security->generateRandomString();
        $this->auth_key = \Yii::$app->security->generateRandomString();
        $this->save();
    }


    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['login' => $username]);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    public static function findByEmailAndCode($email, $code)
    {
        return static::findOne(['email' => $email, 'code' => $code]);
    }


    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function getAccessToken()
    {
        return $this->access_token;
    }


    public function setCode($code)
    {
        $this->code = $code;
        $this->save();
    }

    public function setPassword($password)
    {
        $this->password = \Yii::$app->security->generatePasswordHash($password);
        $this->save();
    }

    public function setAccessToken($token)
    {
        $this->access_token = $token;
        $this->save();
    }


    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function validatePassword($password)
    {
        return \Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }
}
