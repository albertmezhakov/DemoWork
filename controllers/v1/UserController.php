<?php


namespace app\controllers\v1;

use yii\filters\auth\HttpHeaderAuth;

class UserController extends \yii\rest\Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpHeaderAuth::class,
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        return sprintf('%s:%s', \Yii::$app->user->identity->login, \Yii::$app->user->identity->email);
    }

    public function actionReset()
    {
        if (\Yii::$app->user->identity->group != 2) {
            throw new \yii\web\UnauthorizedHttpException('You can only reset the access_token if you are from group 2.');
        }
        $data = array(
            'old' => array(
                'access_token' => \Yii::$app->user->identity->getAccessToken()
            ),
            'new' => array(
                'access_token' => \Yii::$app->security->generateRandomString(50)
            )
        );
        \Yii::$app->user->identity->setAccessToken($data['new']['access_token']);

        return $this->asJson($data);
    }

    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'reset' => ['CREATE'],
        ];
    }
}

#  curl -X CREATE -H "Accept: application/json;" -H "X-Api-Key:<Token>" "http://demowork.local/v1/user/reset"
#  curl -X GET -H "Accept: application/json;" -H "X-Api-Key:<Token>" "http://demowork.local/v1/user/reset"