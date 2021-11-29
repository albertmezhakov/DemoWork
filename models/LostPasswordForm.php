<?php


namespace app\models;


class LostPasswordForm extends \yii\base\Model
{
    public $login_email;


    public function rules()
    {
        return [
            ['login_email', 'required', 'message' => 'Заполните логин и пароль.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'login_email' => 'Логин / почта',
        ];
    }
}