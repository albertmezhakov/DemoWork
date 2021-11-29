<?php


namespace app\controllers;


use app\models\LoginForm;
use app\models\LostPasswordForm;
use app\models\RegisterForm;
use app\models\ResetPasswordForm;
use app\models\User;
use Yii;
use yii\web\Controller;

class AuthController extends Controller
{

    public function actionLogin()
    {

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = User::findByUsername($model->login);
            if (!empty($user)) {
                if (Yii::$app->getSecurity()->validatePassword($model->password, $user['password'])) {
                    $user->auth_key = Yii::$app->security->generateRandomString(50);
                    $user->save();
                    Yii::$app->user->login($user, $model->rememberMe ? 3600 * 24 * 5 : 0);
                    Yii::$app->session->addFlash('success', 'Вы авторизовались под логином ' . $model->login . '.');
                    return $this->goHome();
                }
            }
            Yii::$app->session->addFlash('danger', 'Неправильный логин или пароль.');
        }
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user[0] = User::findByUsername($model->login);
            $user[1] = User::findByEmail($model->email);
            if (empty($user[0]) && empty($user[1])) {
                $user = new User();
                $user->newUser($model->login, $model->email, $model->password2);
                Yii::$app->session->addFlash('success', 'Вы успешно зарегестрировались. Поздравляем Вас.');
                return $this->goHome();
            }
            Yii::$app->session->addFlash('danger', 'Данный логин или почта заняты.');
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionLostPassword()
    {
        $model = new LostPasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user[0] = User::findByUsername($model->login_email);
            $user[1] = User::findByEmail($model->login_email);
            if (!empty($user[0]) || !empty($user[1])) {
                $email = empty($user[0]) ? $user[1]->getEmail() : $user[0]->getEmail();
                $code = Yii::$app->security->generateRandomString();
                empty($user[0]) ? $user[1]->setCode($code) : $user[0]->setCode($code);
                Yii::$app->mailer->compose()
                    ->setFrom('demowork.local@gmail.com')
                    ->setTo($email)
                    ->setSubject('Востановление пароля - DemoWork.local')
                    ->setHtmlBody(sprintf('http://demowork.local/resetpassword?email=%s&code=%s', $email, $code))
                    ->send();
                Yii::$app->session->addFlash('success', 'На вашу почту выслано письмо с ссылке для востановления пароля.');
                return $this->goHome();
            }
            Yii::$app->session->addFlash('danger', 'Данный логин или почта не найдены.');
        }


        return $this->render('lostpassword', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($email = '', $code = '')
    {
        if (!empty($email) && !empty($code)) {
            $user = User::findByEmailAndCode($email, $code);
            if (!empty($user)) {
                $model = new ResetPasswordForm();
                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    $user->setPassword($model->password2);
                    $user->setCode(Yii::$app->security->generateRandomString());
                    Yii::$app->session->addFlash('success', 'Вы успешно востановили пароль.');
                    return $this->goHome();
                }
                return $this->render('resetpassword', [
                    'model' => $model,
                ]);
            }
        }
        return $this->goHome();

    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}