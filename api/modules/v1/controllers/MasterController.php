<?php

namespace api\modules\v1\controllers;

use yii;
use yii\base\Security;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\models\WpCheckPassword;
use app\models\WpCookieCheck;
use app\models\WpPasswordHash;
use yii\rest\ActiveController;
use yii\filters\VerbFilter;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use common\models\ReportAbuse;
use common\models\PostLike;
use common\models\Countries;
use common\models\States;
use common\models\Cities;
use common\models\Post;
use common\models\NeighbourhoodWatch;
use common\models\Common;
use common\components\GeneralComponent;
use common\models\EmailTemplate;
use common\models\SosContact;
use yii\helpers\ArrayHelper;
use common\components\MasterComponent;


class MasterController extends Controller {

   
     public function actionGetCms() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $result = [];
        $data = [];
        $type = $post['type'];
        if (isset($type) && $type != "" ) {
            $terms = \common\models\CmsManagement::findOne(['type'=>$type,'status'=>1]);
            if($terms){
            $adata = array();
            $adata['type'] = $terms['type'];
            $adata['title'] = $terms['cms_title'];
            $adata['description'] = $terms['cms_description'];
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'Success');
            $result['data'] = $adata;
            }
            else{
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'No data found'); 
            }
        } else {

            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'Invalid parameter.');
        }
        return $result;
    }

    
     public function actionSetting() {

        $result = array();
        $settingdata = \common\models\Setting::findOne(1);
        $setting_array = array();
        $gnl = new GeneralComponent();
        $setting_array['question_min_value'] = (int)$settingdata['question_min_value'];
        $setting_array['question_max_value'] = (int)$settingdata['question_max_value'];
        $setting_array['refer_step_1'] = (string) $gnl->image_not_found_api_main('refer_step1', $settingdata['refer_step1']);
        $setting_array['refer_step_2'] = (string)$gnl->image_not_found_api_main('refer_step2', $settingdata['refer_step2']);
        $setting_array['refer_step_3'] = (string)$gnl->image_not_found_api_main('refer_step3', $settingdata['refer_step3']);
        
        $result['status'] = 1;
        $result['message'] = Yii::t('app', 'Success');
        $result['data'] = $setting_array;
            
        header('Content-type: application/json');
        echo json_encode($result);
        exit();
    }
    /* service_token,user_id
     * 
     */
     public function actionUpdateSetting() {

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
       
        $user = User::find()->where(['id'=>$post['user_id'],'status'=>1])->One();
        if(!empty($user)){
        if($user['role'] == 'student'){
            $user->email_on_tutor_accept = (isset($post['email_on_tutor_accept']) && $post['email_on_tutor_accept'] >= 0)?$post['email_on_tutor_accept']:$user->email_on_tutor_accept;
            $user->email_on_question_complete = (isset($post['email_on_question_complete']) && $post['email_on_question_complete'] >= 0)?$post['email_on_question_complete']:$user->email_on_question_complete;
            $user->show_fav_tutor = (isset($post['show_fav_tutor']) && $post['show_fav_tutor'] >= 0)?$post['show_fav_tutor']:$user->show_fav_tutor;
        }
        if($user['role'] == 'tutor'){
            $user->email_on_selection = (isset($post['email_on_selection']) && $post['email_on_selection'] >= 0)?$post['email_on_selection']:$user->email_on_selection;
            $user->email_on_invoice = (isset($post['email_on_invoice']) && $post['email_on_invoice'] >= 0)?$post['email_on_invoice']:$user->email_on_invoice;
        }
        $user->save(FALSE);
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'Success.');
        }
        else {
            $result['status'] = 0;
                   $result['message'] = Yii::t('app', 'No user found.');
        }
            
        
        return $result;
    }
    /* service_token,user_id
     * 
     */
     public function actionUserSettingDetails() {

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
       
        $user = User::find()->where(['id'=>$post['user_id'],'status'=>1])->One();
        if(!empty($user)){
        if($user['role'] == 'student'){
            $data = [
                'email_on_tutor_accept' => (int)$user->email_on_tutor_accept,
                'email_on_question_complete' => (int) $user->email_on_question_complete,
                'show_fav_tutor' => (int)$user->show_fav_tutor,
            ];
        }
         if($user['role'] == 'tutor'){
            $data = [
                'email_on_selection' =>(int) $user->email_on_selection,
                'email_on_invoice' => (int)$user->email_on_invoice,
            ];
        }
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'Setting details.');
            $result['message'] = $data;
        }
        else {
            $result['status'] = 0;
                   $result['message'] = Yii::t('app', 'No user found.');
        }
            
        
        return $result;
    }
    
    /*
     * Question details
     * service_token,user_id(student or tutor),question_id
 */  
    public function actionQuestionDetail() {
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
            
            $mnl = new MasterComponent();
            if($data = $mnl->question_detail((isset($post['question_id']))?$post['question_id']:0,$post['user_id'])){
            
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'My questions list.');
            $result['data'] = $data;
            }
            else{
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'No data found.');
            }
        
        return $result;
    }
    
    /*
     * Question status if completed
     * service_token,user_id(student or tutor),question_id
 */  
    public function actionQuestionStatus() {
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
            
            $mnl = new MasterComponent();
            if($data = $mnl->question_status((isset($post['question_id']))?$post['question_id']:0,$post['user_id'])){
            
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'Question status.');
            $result['data'] = $data;
            }
            else{
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'No data found.');
            }
        
        return $result;
    }
    public function actionReviewOption() {
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
            
            $mnl = new MasterComponent();
            if($data = $mnl->get_review_option_service($post['user_id'])){
            
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'Review Options.');
            $result['data'] = $data;
            }
            else{
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'No data found.');
            }
        
        return $result;
    }
    public function actionReportOption() {
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
            
            $mnl = new MasterComponent();
            if($data = $mnl->get_report_option_service($post['user_id'])){
            
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'Report Options.');
            $result['data'] = $data;
            }
            else{
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'No data found.');
            }
        
        return $result;
    }
    public function actionUserDiscountCheck() {
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
          
            
            $discount = \common\components\StudentComponent::referral_discoun_check($post['user_id']);
            
            

            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'Discount Detail.');
            $result['data'] = $discount;
            
        
            return $result;
    }
    public function actionGetNotification() {
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
        $mnl = new MasterComponent();
        $data = $mnl->notifications($post['user_id'],$post['page']);
            
            return $data;
    }  
      public function actionUnreadNotificationCount() {
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
        $mnl = new MasterComponent();
        $data = $mnl->unread_notifications($post['user_id']);
         $result['status'] = 1;
            $result['message'] = Yii::t('app', 'Unread notifications count.');
            $result['data'] = $data;
            
        
            return $result;
    } 
     public function actionRemoveNotification() {
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
        $mnl = new MasterComponent();
        $mnl->remove_notification($post['notification_id']);
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'Notification removed.');
            
        
            return $result;
    } 
}

?>
