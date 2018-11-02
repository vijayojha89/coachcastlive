<?php

namespace api\modules\v1\controllers;

use yii;
use yii\base\Security;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\rest\ActiveController;
use yii\filters\VerbFilter;
use common\models\User;
use common\components\GeneralComponent;
use common\models\EmailTemplate;
use common\models\Friend;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use common\models\UserInvited;
use frontend\models\DeviceToken;
use frontend\models\Review;
use common\models\ReportAbuse;
use common\components\StudentComponent;
use frontend\models\UserPaymentInfo;
class UserController extends Controller {

    function base64_to_jpeg($base64_string, $output_file) {
        $ifp = fopen($output_file, "wb");

        $data = explode(',', $base64_string);

        fwrite($ifp, base64_decode($data[1]));
        fclose($ifp);

        return $output_file;
    }

    public static function checkprofilepic($profilepic) {
        if (empty($profilepic))
            return;

        if (strpos($profilepic, 'https') !== false || strpos($profilepic, 'http') !== false) {
            return $profilepic;
        } else {
            return IMG_URL1 . $profilepic;
        }
    }

    /**
     * User singup
     * @param common: token, user_type, first_name, last_name, email, password, device_token, device_type 
     * @param student: referral_code, qualification_id, subject_id
     * OR
     * @param tutor: expertise_id, cv_doc(optional), company_name,  bank_name, account_no, sort_code
     */
    public function actionSignup() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $result = $data = [];
        $gnl = new GeneralComponent();
        
        if(Yii::$app->request->post())
        {
         if(isset($post['referral_code'])){
             $referral_code = User::find()->where(['student_referral_code'=>$post['referral_code']])->asArray()->One();
             if(empty($referral_code)){
             $result['status'] = 0;
             $result['message'] = Yii::t('app', 'Referral code does not exist.');
             return $result;
             }
         }
            if ($this->checkUniqueEmail(@$post['email']))
            { 
                    $result['status'] = 0;
                    $result['message'] = Yii::t('app', 'Email address already registered.');
            }
            else
            {
                    /*
                    if($post['referral_code'])
                    {    
                        if ($this->checkRefferalCode($post['referral_code']))
                        {
                            $result['status'] = 0;
                            $result['message'] = Yii::t('app', 'Invalid referral code.');
                        }
                    } 
                     * 
                     */   

            $model = new \frontend\models\User;
            if($post['subject_ids'] != ''){
            $subject_ids = explode(',', $post['subject_ids']);
            $replacement_subject_ids = array('subject_ids' => $subject_ids);
            $post = array_replace($post, $replacement_subject_ids);        
            }
            if($post['expertise_ids'] != ''){
            $expertise_ids = explode(',', $post['expertise_ids']);
            $replacement_expertise_ids = array('expertise_ids' => $expertise_ids);
            $post = array_replace($post, $replacement_expertise_ids);
            }
            
                    if ($model->signup($post))
                    {   
         $userdetail = User::find()->where(["email" => $post['email'], "status" => 1])->one(); 
         $profile_user = new User();
                       $result['status'] = 1;
                       $result['message'] = Yii::t('app', 'Sign up successfull. Please verify your email address to activate account');
                       $result['data'] = $profile_user->user_detail_response_webservice($userdetail['id']);
                       $result['FILES'] = $_FILES;
                    }
                    else
                    {
                        $result['status'] = 0;
                        $result['message'] = Yii::t('app', 'Sign up failed.');
                    }
                        
            }
            
        } 
        else
        {
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'Invalid parameters.');
        }
        return $result;
    }

    /**
     * User login
     * normal login with email & password
     */
    public function actionLogin() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $result = [];
        if(Yii::$app->request->post())
        {

            // Normal login
    if ($post['social_login_type'] == '')
            {
                $userdetail = User::find()->where('email = "'.$post['email'] . '" ')->orderBy(['id' => SORT_DESC])->one();;
          if(empty($userdetail) )
          {             
                        $result['status'] = 0;
                        $result['message'] = \Yii::t('app', 'Email does not exist.');
          }
          else if($userdetail['role']== 'subAdmin' || $userdetail['role']== 'superAdmin' )
          {             
                        $result['status'] = 0;
                        $result['message'] = \Yii::t('app', 'You are not authorized to access.');
          }
          else if($userdetail['status']== 0 )
          {             
                        $result['status'] = 0;
                        $result['message'] = \Yii::t('app', 'Your account has been deactivated. Please contact admin.');
          }
          else if($userdetail['status']== 2 )
          {             
                        $result['status'] = 0;
                        $result['message'] = \Yii::t('app', 'Your account has been deleted.');
          }
          else if($userdetail['password_hash'] == '' || $userdetail['password_hash'] == NULL) 
          {             
                        $result['status'] = 0;
                        $result['message'] = \Yii::t('app', 'This Account Is Registered With Facebook Or Google.');
          }
          else if($userdetail['email_verified'] == 0) 
          {             
                        $result['status'] = 0;
                        $result['message'] = \Yii::t('app', 'Your email address is not verified.');
          }
          else if (!empty($userdetail) )
                {
                    $password_hash = $userdetail['password_hash'];
                    if (Yii::$app->getSecurity()->validatePassword($post['password'], $password_hash))
                    { 
                            $user_device = User::find()->where(['id' => $userdetail->id])->one();
                        
                            if (isset($post['device_type']) && $post['device_token'] !== '')
                            {
                                
                                $user_device->device_token = @$post['device_token'];
                                $user_device->device_type = @$post['device_type'];
//                                $user_device->device_id = @$post['device_id'];
                            }
                                $service_token = Yii::$app->security->generateRandomString();
                                $user_device->service_token = $service_token;
                                $user_device->save(false);
                            
                            $usermodel = new User();
                            $response = $usermodel->user_detail_response_webservice($userdetail->id);
                            
                             
                             /* zoho create contact */
                            $zoho_model = new \common\models\Zohoinvoice();
                            $zoho_model->zoho_create_contact($userdetail->id);
                            /* end zoho create contact */
                            
                            if($response['role'] == 1)
                            {
                                /* stripe create account */
                                $userobj = new \common\models\User();
                                $userobj->stripe_customer_create($userdetail->id);
                                 /* end  stripe create account */

                            }    
                            
                            
                            $result['status'] = 1;
                            $result['message'] = Yii::t('app', 'Login successful.');
                            $result['data'] = $response;
                       
                    }
                    else
                    {
                        $result['status'] = 0;
                        $result['message'] = \Yii::t('app', 'Incorrect Password.');
                    }
                }
                else
                {
                    $result['status'] = 0;
                    $result['message'] = \Yii::t('app', 'User not found.');
                }

                // Social login
            }
            else
            {
                if (isset($post['social_login_id']) && !empty($post['social_login_id']))
                {
                    $userdetail = User::find()->where('social_id = "' . $post['social_login_id'] . '" AND social_type = "'.$post['social_login_type'].'" AND (role = "tutor" OR role = "student")')->orderBy(['id' => SORT_DESC])->one();
                    if (empty($userdetail))
                    {
                        if (isset($post['email']) && !empty($post['email']))
                        {
                            $userdetail = User::find()->where('email = "' . $post['email'] . '" AND (role = "tutor" OR role = "student")')->orderBy(['id' => SORT_DESC])->one();
                            if($userdetail)
                            {    
                                $userdetail->social_id = @$post['social_login_id'];
                                $userdetail->social_type = @$post['social_login_type'];
                                $userdetail->save(false);
                            }   
                        }
                    }

                    if (!empty($userdetail))
                    {
                        if($userdetail['email_verified'] == 0) 
                        {             
                        $result['status'] = 0;
                        $result['message'] = \Yii::t('app', 'Your email address is not verified.');
                        return $result;
                        }
                        
                        if($userdetail['status'] == 1)
                        {    
                            $user_device = User::find()->where(['id' => $userdetail->id])->one();
                            if (isset($post['device_type']) && $post['device_type'] !== '') {
                                $user_device->device_token = @$post['device_token'];
                                $user_device->device_type = @$post['device_type'];
//                                $user_device->device_id = @$post['device_id'];
                            }
                                $service_token = Yii::$app->security->generateRandomString();
                                $user_device->service_token = $service_token;
                                $user_device->save(false);

                            $usermodel = new User();
                            $response = $usermodel->user_detail_response_webservice($userdetail->id);
                            
                            
                             /* zoho create contact */
                            $zoho_model = new \common\models\Zohoinvoice();
                            $zoho_model->zoho_create_contact($userdetail->id);
                            /* end zoho create contact */
                            
                            if($response['role'] == 1)
                            {
                                /* stripe create account */
                                $userobj = new \common\models\User();
                                $userobj->stripe_customer_create($userdetail->id);
                                 /* end  stripe create account */

                            }    
            
                            
                            $result['status'] = 1;
                            $result['message'] = Yii::t('app', 'Login successfull.');
                            $result['data'] = $response;
                        }
                        else
                        {
                            $result['status'] = 0;
                            $result['message'] = Yii::t('app', 'Your account in In-active.');
                        }    
                        
                    }
                    else
                    {
                        $result['status'] = 2;
                        $result['message'] = Yii::t('app', 'Need to complete profile.');
                    }
                    
                } else {
                    $result['status'] = 0;
                    $result['message'] = Yii::t('app', 'Invalid parameters.');
                }
            }
        } else {
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'Please enter a required valid parameter.');
        }

        return $result;
    }
public function actionLogout() {
         \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $result = [];
        if(trim(@$post['user_id']))
            {
            
                $usercheck = GeneralComponent::verify_token($post['service_token']);
                if(!$usercheck)
                {
                        $result['status'] = 5;
                        $result['message'] = SERVICE_TOKEN_EXPIRE_MSG;
                        return $result;
                } 
            
            }
            $user = User::find()->where(['id' => $post['user_id'], 'status' => 1])->one();
            if (empty($user)) {
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'User not found');
                return $result;
            }
            else if (count($user) > 0) {
                $user->device_token = $user->device_type = "";

                $user->save(false);
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'success');
            } 
            return $result;
    }
    /**
     * check is signup email is unique
     */
    public function checkUniqueEmail($email) {
        $count = \frontend\models\User::find()->where(['email'=>$email,'status' => [0,1]])->count();

        if ($count >= 1) {
            return true;
        } else {
            return false;
        }
    }
    public function checkRefferalCode($referral_code) {
        $count = \frontend\models\User::find()->where('student_referral_code = "' . $referral_code . '" AND status != 2')->count();

        if ($count >= 1) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * User change password
     * compulsory params - old_password,new_password
     */
    public function actionChangePassword() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $result = [];
        $data = [];
        if(trim(@$post['user_id']))
            {
            
                $usercheck = GeneralComponent::verify_token($post['service_token']);
                if(!$usercheck)
                {
                        $result['status'] = 5;
                        $result['message'] = SERVICE_TOKEN_EXPIRE_MSG;
                        return $result;
                } 
            
            }
        
            $model = User::find()->where(["id" => $post['user_id'], 'status' => 1])->one();
            if (count($model) > 0) {


                $check_pass_valid = $this->validatePassword($post['old_password'], $model);
                if ($check_pass_valid == true || $check_pass_valid == 1) {

                    $model->password_hash = Yii::$app->security->generatePasswordHash($post['new_password']);
                    $model->save(false);
                    $result['status'] = 1;
                    $result['message'] = Yii::t('app', 'Password updated successfully.');
                } else {
                    $result['status'] = 0;
                    $result['message'] = Yii::t('app', 'Please enter valid current password.');
                }
            } else {
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'User does not exists.');
            }
        
            return $result;
    }

    /**
     * User password validation
     */
    private function validatePassword($password, $model) {

        if (strlen($model->password_hash) == 60) {
//standard validation 
            return Yii::$app->security->validatePassword($password, $model->password_hash);
        } else {
//wordpress validation
            $WpCheckPassword = new WpCheckPassword();
            if ($WpCheckPassword->wp_check_password($password, $model->password_hash, $model->id)) {
                $model->password_hash = Yii::$app->security->generatePasswordHash($password);
                $model->auth_key = Yii::$app->security->generateRandomString();
                if ($model->save(false)) {
                    return true;
                } else {
//                    Yii::$app->session->setFlash('danger', 'ERROR: Password cannot be verified now!Please contact support');
                }
            } else {
                return false;
            }
        }
    }

    /**
     * User forget password
     * mandatory params - email
     */
    public function actionForgotPassword() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $result = [];
        if (isset($post['email']) && $post['email'] != "") {
            $userdetail = User::find()->where('email = "' . $post['email'] . '" AND (role = "tutor" OR role = "student") AND status IN (0,1)')->one();
            if (!empty($userdetail))
            {
                if ($userdetail->status == 0) {
                    $result['status'] = 0;
                    $result['message'] = Yii::t('app', 'You are not authorized to access.');
                    return $result;
                } else if ($userdetail->status == 1) {
                    $result['status'] = 0;
                    $result['message'] = Yii::t('app', 'Your account has been deactivated. Please contact admin.');
                    return $result;
                } 
                else {
                    $model = new \frontend\models\PasswordResetRequestForm();
                    $model->email = $post['email'];
                    if (!empty($model->email)) {
                        if ($model->sendEmail()) {
                            $result['status'] = 1;
                            $result['message'] = Yii::t('app', 'Check your email for further instructions.');
                        } else {
                            $result['status'] = 0;
                            $result['message'] = Yii::t('app', 'Sorry, we are unable to reset password for email provided.');
                        }
                    }
                }
            } else {
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'Email is not registered.');
            }
        } else {
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'Invalid parameters.');
        }
        return $result;
    }

    /**
     * Get master data list
     */
    public function actionGetMasterlist() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $result = [];
        $list = [];
        $data = [];

        
        if(trim(@$post['user_id']))
            {
            
                $usercheck = GeneralComponent::verify_token($post['service_token']);
                if(!$usercheck)
                {
                        $result['status'] = 5;
                        $result['message'] = SERVICE_TOKEN_EXPIRE_MSG;
                        return $result;
                } 
            
            }
            
            
        if (isset($post['type']) && $post['type'] != "") {
            $genral_component = new GeneralComponent();
            $list = $genral_component->getMasterList($post['type']);
            if (!empty($list)) {
                foreach ($list as $key => $value) {
                    $data[] = ['id' => $key, 'value' => $value];
                }
            }
            $result['status'] = 1;
            $result['data'] = $data;
        } else {
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'Invalid parameters.');
        }
        return $result;
    }

    /**
     * Get CMS page data.
     */
    public function actionGetCms() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $result = [];
        $data = [];

        if (isset($post['cms_id']) && $post['cms_id'] != "") {
            $genral_component = new GeneralComponent();
            $data = $genral_component->getCms($post['cms_id']);
            if (!empty($data)) {
                $result['status'] = 1;
                $result['data'] = $data;
            } else {
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'Page not found.');
            }
        } else {
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'Invalid parameters.');
        }
        return $result;
    }

    /**
     * Get user profile
     * Param : user_id
     */
    public function actionProfileInformation() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $result = [];
        $data = [];
        $profile_user = new User();
  
            $userdetail = User::find()->where(["id" => $post['user_id'], "status" => 1])->one();
            $gnl = new GeneralComponent();
            $snl = new StudentComponent();
            if (count($userdetail) > 0) {
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'success');
                $result['data'] = $profile_user->user_detail_response_webservice($userdetail['id']);
            } else {
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'User does not exists');
            }
        
        header('Content-type: application/json');
        echo json_encode($result);
        exit();
    }

    /**
     * User profile update
     * compulsory params - user_id
     * optional params - age,gender,country_id,state_id,suburb_id,street_id,address,latitude,longitude,is_notification,newsletter_onoff,profile_photo,bill_image
     */
    public function actionUpdateProfile() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $result = [];
        $data = [];
        $profile_user = new User();
        if(trim(@$post['user_id']))
            {
            
                $usercheck = GeneralComponent::verify_token($post['service_token']);
                if(!$usercheck)
                {
                        $result['status'] = 5;
                        $result['message'] = SERVICE_TOKEN_EXPIRE_MSG;
                        return $result;
                } 
            
            }
       
            $model = new \frontend\models\User;
            if($post['subject_ids'] != ''){
            $subject_ids = explode(',', $post['subject_ids']);
            $replacement_subject_ids = array('subject_ids' => $subject_ids);
            $post = array_replace($post, $replacement_subject_ids);        
            }
            if($post['expertise_ids'] != ''){
            $expertise_ids = explode(',', $post['expertise_ids']);
            $replacement_expertise_ids = array('expertise_ids' => $expertise_ids);
            $post = array_replace($post, $replacement_expertise_ids);
            }
            
                    if ($model->updateprofile($post))
                    {
                       $result['status'] = 1;
                       $result['message'] = Yii::t('app', 'Profile updated successfully');
                       $result['data'] = $profile_user->user_detail_response_webservice($post['user_id']);
                    }
                    else
                    {
                        $result['status'] = 0;
                        $result['message'] = Yii::t('app', 'Failed to update profile.');
                    }
        
                    return $result;
    }

/**
     * reportabuse
     * service_token,user_id(reoprt_abuse_by),report_abuse_for,option_id,comment
     */
    public function actionReportAbuse() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $result = [];
        $data = [];
        if(trim(@$post['user_id']))
            {
            
                $usercheck = GeneralComponent::verify_token($post['service_token']);
                if(!$usercheck)
                {
                        $result['status'] = 5;
                        $result['message'] = SERVICE_TOKEN_EXPIRE_MSG;
                        return $result;
                } 
            
            }
        $report = ReportAbuse::find()->where(['question_id'=>$post['question_id'],'reoprt_abuse_by'=>$post['user_id'],'report_abuse_for'=>$post['report_abuse_for']])->one();
        if(!empty($report)){
                        $result['status'] = 0;
                        $result['message'] = 'Already done';
                        return $result;
        }
            $user_by = User::findOne($post['user_id']);
            $user_for = User::findOne($post['report_abuse_for']);
            $model = new ReportAbuse();
            $model->question_id=$post['question_id'];
            $model->option_id=$post['option_id'];
            $model->comment=$post['comment'];
            $model->reoprt_abuse_by=$post['user_id'];
            $model->report_abuse_for=$post['report_abuse_for'];
            $model->created_date=date("Y-m-d H:i:s");
            $model->roport_abuse_by_role=$user_by['role'];
            $model->roport_abuse_for_role=$user_for['role'];
            if($model->save(FALSE))
            {
                $question = \common\models\Question::findOne($post['question_id']);
                if($user_by['role'] == 'tutor')
                {    
                   $question->question_status = 6;
                }
                if($user_by['role'] == 'student')
                {
                    $question->question_status = 7;
                }
                $question->completed_date = date("Y-m-d H:i:s");
                $question->save(FALSE);
                
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'This user is reported.');
            }
            else{
                $result['status'] = 0;
                 $result['message'] = Yii::t('app', 'Error.');
            }
            
        
        return $result;
    }      
    /*
     *  all details of user
     */
    public function actionUser() {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $result = [];
        $data = [];
        if(trim(@$post['user_id']))
            {
            
                $usercheck = GeneralComponent::verify_token($post['service_token']);
                if(!$usercheck)
                {
                        $result['status'] = 5;
                        $result['message'] = SERVICE_TOKEN_EXPIRE_MSG;
                        return $result;
                } 
            
            }
            $user_id = $post['user_id'];
            $model = User::find()->where(['id' => $user_id, 'status' => 1])->one();
            if ($model) {
                            $usermodel = new User();
                            $data = $usermodel->user_detail_response_webservice($user_id);
                            $data['device_type'] = (string)$model->device_type;
                            $data['device_token'] = (string)$model->device_token;
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'Success');
                $result['data'] = $data;
            } else {
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'No record found');
            }
       

        return $result;
    }   
}
