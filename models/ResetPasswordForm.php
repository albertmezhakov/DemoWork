<?php


namespace app\models;


class ResetPasswordForm extends \yii\base\Model
{
    public $password1;
    public $password2;


    public function rules()
    {
        return [
            [['password1', 'password2'], 'required', 'message' => 'Заполните все поля.'],
            ['password1', 'compare', 'compareAttribute' => 'password2', 'message' => 'Пароли должны быть одиноковы.'],
            [['password1', 'password2'], 'match', 'pattern' => '/^[A-Za-z0-9_-]*$/', 'message' => 'Можно использовать только английские буквы, цифры, _, -.'],
            [['password1', 'password2'], 'string', 'length' => [5, 50]]
        ];
    }

    public function attributeLabels()
    {
        return [
            'password1' => 'Пароль',
            'password2' => 'Повторите пароль',
        ];
    }
}