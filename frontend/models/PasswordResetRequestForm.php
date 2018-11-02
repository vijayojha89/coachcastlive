<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model {

    public $email;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'emailcheck'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with such email.'
            ],
        ];
    }
    
    
    public function emailcheck($attribute, $params)
    {
        $user_not_auth = User::find()
                ->where("email = '$this->email' AND status = 1 AND role = 'tutor'")
                ->orWhere("email = '$this->email' AND status = 1 AND role = 'student'")
                ->One();
         if(empty($user_not_auth)){
           $this->addError($attribute, 'You are not authorized to access.');
         }
         else {
               return true;
          }
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail() {
        /* @var $user User */
        $user = User::findOne([
                    'status' => User::STATUS_ACTIVE,
                    'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save(FALSE)) {
                return false;
            }
        }
        $settings = \common\models\Setting::findOne(['status' => 1]);
        $logoimage = Yii::$app->params['httpurl'] . 'setting_logo_image/' . $settings['setting_logo_image'];
        $data = Yii::$app->mailer
                ->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $user, 'logoimage' => $logoimage])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' Support'])
                ->setTo($this->email)
                ->setSubject('Password reset for ' . Yii::$app->name)
                ->send();
        return true;
    }

}
/* SENDMAIL */
