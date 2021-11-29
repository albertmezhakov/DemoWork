<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $login;
    public $password;
    public $rememberMe = true;

    public function rules()
    {
        return [
            [['login', 'password'], 'required', 'message' => 'Заполните логин и пароль.'],
            ['rememberMe', 'boolean'],
            [['login', 'password'], 'match', 'pattern' => '/^[A-Za-z0-9_-]*$/', 'message' => 'Можно использовать только английские буквы, цифры, _, -.']
        ];
    }

    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомни меня',
        ];
    }
}
