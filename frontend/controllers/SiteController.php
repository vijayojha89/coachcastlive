<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\User;
use yii\widgets\ActiveForm;
use yii\web\Response;
use kartik\mpdf\Pdf;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public $enableCsrfValidation = false;
    

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
            'eauth' => [
                // required to disable csrf validation on OpenID requests
                'class' => \nodge\eauth\openid\ControllerBehavior::className(),
                'only' => ['login'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    
    
    
 
public function actionInvoicepdf() {
    
    $content = $this->renderPartial('invoicepdf');
        $pdf = new Pdf([
        'mode' => Pdf::MODE_CORE, 
        'content' => $content,
        'cssFile' => '@frontend/web/css/invoice.css',
        'options' => [
            'title' => 'CoachCast Live',
            'subject' => 'Invoices'
        ],
        'methods' => [
             //     'SetHeader' => ['Invoices || Date : ' . date("m/d/Y")],
            //    'SetFooter' => ['|Page {PAGENO}|'],
        ]
    ]);
    
    echo $pdf->render();
    die;
}
   

    
    public function actionTest()
    {
        $studentdata = \common\models\EmailTemplate::findOne(14);
        
        $gobj = new \common\components\GeneralComponent();
        $gobj->sendMail('vijay.o@virtualveb.com',$studentdata->emailtemplate_subject,$studentdata->emailtemplate_body);
        
    }        
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex() 
    {
        
        if(!Yii::$app->user->isGuest)
        {
            $userdata = \common\models\User::findIdentity(Yii::$app->user->id); 
            if($userdata['role'] == "user")
            {
                return $this->redirect(['user/myappointment']);
            }
            else
            {
                     return $this->redirect(['trainer/schedules']);
                
            }   
            return $this->render('index');
        }    
        else
        {    
            return $this->render('index');
        }    
    }

    
    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin() {
        
        if (!@$_REQUEST) {            
            return $this->goHome();
        }

        $serviceName = Yii::$app->getRequest()->getQueryParam('service');
        if (isset($serviceName)) {
            /** @var $eauth \nodge\eauth\ServiceBase */
            $eauth = Yii::$app->get('eauth')->getIdentity($serviceName);
            $eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
            $eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('site/login'));
            try {
                if ($eauth->authenticate()) {
                    $attributes = $eauth->getAttributes();
                    $name = '';
                    $email = '';
                    $social_type = '';
                    $social_login_id = '';

                    if ($serviceName == 'google') 
                    {
                        $social_type = 2;
                        $social_login_id = $attributes['id'];
                        $email = $attributes['email'];
                        $name = $attributes['name'];
                        
                        
                        $user_model = \common\models\User::find()
                                        ->where([
                                            'social_id' => $social_login_id,
                                            'social_type' => $social_type,
                                            'status' => 1
                                        ])->one();
                        
                           
                       
                    }
                    else if ($serviceName == 'facebook') 
                    {
                        $social_type = 1;
                        $social_login_id = $attributes['id'];
                        $email = $attributes['email'];
                        $name = $attributes['name'];

                        $user_model = \common\models\User::find()
                                        ->where([
                                            'social_id' => $social_login_id,
                                            'social_type' => $social_type,
                                            'status' => 1
                                        ])->one();
                    }

                    Yii::$app->session->set('social_login', ['social_type' => $social_type, 'social_login_id' => $social_login_id, 'email' => $email, 'name' => $name]);

                    if (!empty($user_model)) { 
                            if($user_model->email_verified == 0)
                            {
                                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Please verify your account'));  
                                return $this->redirect(['site/index']);
                            }
                        if (Yii::$app->user->login($user_model)) { 
                           // Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Login Successful'));  
                            return $this->redirect(['site/index']);
                        } else {
                            return $this->redirect(['/?incomplete=1']);
                        }
                    } 
                    elseif (empty($user_model) && $email != '') 
                    {
                        $user_model = \common\models\User::find()->where(['email' => $email, 'status' => 1])->one();
                        if($user_model)
                        {
                            Yii::$app->db->createCommand()->update('user',['social_type'=>$social_type,'social_id'=>$social_login_id], "id=$user_model->id")->execute();
                         }    
                        
                        
                        
                        if (!empty($user_model) && Yii::$app->user->login($user_model))
                        {
                            return $this->redirect(['/']);
                        }
                        else
                        {
                            return $this->redirect(['/?incomplete=1']);
                        }
                    }
                    else
                    {
                        return $this->redirect(['/?incomplete=1']);
                    }

                    // special redirect with closing popup window
                    $eauth->redirect();
                } else {
                    // close popup window and redirect to cancelUrl
                    $eauth->cancel();
                }
            } catch (\nodge\eauth\ErrorException $e) {
                // save error to show it later
                Yii::$app->getSession()->setFlash('error', 'EAuthException: ' . $e->getMessage());

                // close popup window and redirect to cancelUrl
//				$eauth->cancel();
                $eauth->redirect($eauth->getCancelUrl());
            }
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        Yii::$app->user->logout();
        //return $this->goHome();
        return $this->redirect(['/']);
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact() {
       $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                        'model' => $model,
            ]);
        }
       
       //  return $this->render('contact-us');
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout() {
        return $this->render('about');
    }
    
    
     public function actionChatwithtrainer() {
        return $this->render('chatwithtrainer');
    }


    public function actionComingsoon() {
        return $this->render('comingsoon');
    }
    
     public function actionLiveFitnessClasses() {
        return $this->render('livefitness');
    }

     public function actionTrainers() {
        return $this->render('trainers');
    }
      public function actionTrainerVideo() {
        return $this->render('trainer-video');
    }

    
    public function actionPress() {
        return $this->render('press');
    }
    
    
    
    public function actionHowitworks() {
        return $this->render('howitworks');
    }
    
    
    public function actionCareers() {
        return $this->render('careers');
    }
    
    public function actionCareersdetail($id) {
       
        $careerdetail = \common\models\Career::findOne($id);
        if($careerdetail)
        {   
            return $this->render('careersdetail',['careerdetail'=>$careerdetail]);
        }
        else
        {
            Yii::$app->session->setFlash('error', 'Page not found.');
            return $this->redirect(['/']);
        }    
       
        //
    }
    
    public function actionFaq() {
        return $this->render('faq');
    }
    
    
    public function actionTerms() {
        return $this->render('terms');
    }
    
    
     public function actionPrivacy() {
        return $this->render('privacy');
    }
    
    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup() {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
                    'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
                return $this->goHome();
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('requestPasswordResetToken', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

    /*
     * frontend/views/layouts/header.php
     */

    public function actionNeedHelp() {
        return $this->renderPartial('needhelp');
    }

    /*
     * Choose sign in option
     */

    public function actionSignin() {
        return $this->renderPartial('signin');
    }

    /*
     * Sign in by email and password
     */

    public function actionEmailLogin() {

        $model = new LoginForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } 
        else if ($model->load(Yii::$app->request->post()) && $model->login()) {
           // Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Login successful'));
            return $this->redirect(['site/index']);
//                return $this->goBack();
        } 

        return $this->render('email_login', [
                            'model' => $model,
                ]);
            
        
    }
    
    

    /**
     * Account varification
     *
     * @return mixed
     */
    public function actionVerification($auth) {
        $user = \frontend\models\User::find()->where(["auth_key" => $auth, "email_verified" => 0])->one();

        if (isset($user->id) && $user->id > 0) {
            $user->email_verified = 1;
            $user->auth_key = NULL;

            if (!$user->save(false)) {
                $user = null;
                Yii::$app->session->setFlash('error', Yii::t('app', 'Invalid authentication token.'), true);
            } else {

//-----------------------------mail-to-user-start--------------------------------------------
                $gnl = new \common\components\GeneralComponent();
                $email_model = \common\models\EmailTemplate::findOne(4);
                $subject = str_replace('{appname}', Yii::$app->name, $email_model->emailtemplate_subject);
                
                
                $bodymessage = $email_model->emailtemplate_body;
                $bodymessage = str_replace('{username}', $user->first_name.' '.$user->last_name, $bodymessage);
                $bodymessage = str_replace('{appname}', Yii::$app->name, $bodymessage);
                $messegedata = str_replace('{url}', $url, $bodymessage);
                $gnl->sendMail($user->email, $subject, $messegedata);
//-----------------------------mail-to-user-end--------------------------------------------                          
                Yii::$app->session->setFlash('success', Yii::t('app', 'Your account has been activated! You can now login.'), true);
            }
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Invalid authentication token.'), true);
        }
        return $this->redirect(['/']);
    }
    
          
   public function actionTestemail()
   {
       
        $gnl = new \common\components\GeneralComponent();
                $email_model = \common\models\EmailTemplate::findOne(1);
            $subject = $email_model->emailtemplate_subject;
            $bodymessage = $email_model->emailtemplate_body;
            $bodymessage = str_replace('{firstname}', 'vijay ojha', $bodymessage);
            $gnl->sendMail('vijay.ojha89@gmail.com', $subject, $bodymessage);
        
   }        

}
/* SENDMAIL */