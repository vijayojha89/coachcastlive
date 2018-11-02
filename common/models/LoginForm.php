<?php

namespace common\models;

use Yii;
use yii\base\Model;

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
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, "Incorrect email or password.");
            }
        }
    }
    
    public function emailcheck($attribute, $params)
    {
         $user_deleted = User::find()->where([ 'status' => 1, "email" => $this->email,"role"=>['superAdmin','subAdmin']])->orderBy(['id' => SORT_DESC])->One();
          if(empty($user_deleted))
          {
           $this->addError($attribute, 'You are not authorized to access.');
          }
         else {
               return true;
         }
         
         $admin_deactive = User::find()->where(["email" => $this->email,'role'=>'subAdmin'])->andWhere(['!=','status',1])->orderBy(['id' => SORT_DESC])->One();
         if(!empty($admin_deactive)){
           $this->addError($attribute, 'Your account has been deactivated. Please contact admin');
         }
           $user_exist = User::find()->where(["email" => $this->email])->One();
          if(empty($user_exist)){
           $this->addError($attribute, 'Email does not exist.'); 
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
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
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
