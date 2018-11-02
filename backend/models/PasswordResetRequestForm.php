<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\components\GeneralComponent;
/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with such email.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    { 
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
            if (!$user->save(false)) {
                return false;
            }
        }
        
        $settings = \common\models\Setting::findOne(['status'=>1]);    
        $userdata = \common\models\User::find()->where(['role'=> 'superAdmin'])->one(); 
        $logoimage =  Yii::$app->params['httpurl'] .'setting_logo_image/'.$settings['setting_logo_image'];
        $data =  Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user,'logoimage' => $logoimage]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' Support'])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . $userdata['first_name'])
            ->send();
        return true;
        /*
        $gnl = new \common\components\GeneralComponent();
        
        $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
        $forgotpasswordmsg = '<div class="password-reset">
                                <p>Follow the link below to reset your password:</p>
                                <p>'.$resetLink.'</p>
                              </div>';
        
        $gnl->sendMail($user->email,'Reset Password',$forgotpasswordmsg,$from="");
        return true;*/
    }
}
