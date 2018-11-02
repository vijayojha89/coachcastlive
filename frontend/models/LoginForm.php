<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
/**
 * Login form
 */
class LoginForm extends Model {

    public $email;
    public $password;
    public $rememberMe = true;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['email', 'emailcheck'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword', 'skipOnEmpty' => false],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            
//            else if(isset($user->mobile_verified) && $user->mobile_verified == 0) {
//                $this->addError($attribute, 'Your registration not verified by admin. Please contact admin.');
//            }
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, "Incorrect email or password.");
            }
        }
    }
    
    public function emailcheck($attribute, $params)
    {
        $user_exist = User::find()->where(["email" => $this->email])->orderBy(['id' => SORT_DESC])->one();
        
         if(empty($user_exist)){
           $this->addError($attribute, 'Email does not exist.'); 
         }
         $user_deleted = User::find()->where([ "email" => $this->email])->orderBy(['id' => SORT_DESC])->One();
         if($user_deleted['role']== 'subAdmin' || $user_deleted['role']== 'superAdmin' )
          {
                $this->addError($attribute, 'You are not authorized to access.');
          }
          if($user_deleted['status']== 0 )
          {
                $this->addError($attribute, 'Your account has been deactivated. Please contact admin.');
          }
          if($user_deleted['status']== 2 )
          {
                $this->addError($attribute, 'Your account has been deleted.');
          }
          if($user_deleted['password_hash'] == "" || $user_deleted['password_hash'] == NULL )
          {
                $this->addError($attribute, 'This account is registered with facebook or google.');
          }
          if($user_deleted['email_verified'] == 0) {
                $this->addError($attribute, 'Your email address is not verified.');
          }
    }
    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate()) {

            $model = User::findByEmailLogin($this->email);
            if ($model->user_last_login == '') {
                $model->user_last_login = 'first_login';
            } else {
                $model->user_last_login = time();
            }
            $model->save(false);
                if($this->rememberMe == 1){ 
                    $newCookie0= new \yii\web\Cookie();
                    $newCookie0->name='_email';
                    $newCookie0->value=$this->email;
                    $newCookie0->expire = time()+3600*24*4;
                    $cookie0=Yii::$app->getResponse()->getCookies()->add($newCookie0);
                    $newCookie1= new \yii\web\Cookie();
                    $newCookie1->name='_password';
                    $newCookie1->value=$this->password;
                    $newCookie1->expire = time()+3600*24*4;
                    $cookie1=Yii::$app->getResponse()->getCookies()->add($newCookie1); 
                }
                else {

                }
            //return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
                return Yii::$app->user->login($this->getUser(),0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser() {
        if ($this->_user === null) {
            $this->_user = User::findByEmailLogin($this->email);
        }

        return $this->_user;
    }

    public function attributeLabels() {
        return [
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password')
        ];
    }

}
