<?php


namespace app\widgets;


use yii\base\Widget;

class TestWidget extends Widget
{
    public $status;

    public function init()
    {
        parent::init();
        if (\Yii::$app->user->isGuest) {
            $this->status = 0;
        }else{
            $this->status = \Yii::$app->user->identity->status;
        }
    }

    public function run()
    {
        switch ($this->status){
            case 0:
                echo "Вы гость.";
                break;
            case 1:
                echo "Вы авторизорованный пользователь.";
                break;
            case 2:
                echo "Вы авторизорованный пользователь с правами администратора.";
                break;
        }
    }
}