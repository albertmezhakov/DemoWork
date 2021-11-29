<?php


namespace app\models;


use yii\base\Model;

class RegisterForm extends Model
{
    public $login;
    public $password1;
    public $password2;
    public $email;


    public function rules()
    {
        return [
            [['login', 'password1', 'password2', 'email'], 'required', 'message' => 'Заполните все поля.'],
            ['password1', 'compare', 'compareAttribute' => 'password2', 'message' => 'Пароли должны быть одиноковы.'],
            [['login', 'password1', 'password2'], 'match', 'pattern' => '/^[A-Za-z0-9_-]*$/', 'message' => 'Можно использовать только английские буквы, цифры, _, -.'],
            ['email', 'email'],
            ['login', 'string', 'length' => [3, 25]],
            [['password1', 'password2'], 'string', 'length' => [5, 50]]
        ];
    }

    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'password1' => 'Пароль',
            'password2' => 'Повторите пароль',
            'email' => 'Электронная почта',
        ];
    }
}