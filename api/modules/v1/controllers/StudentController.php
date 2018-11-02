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
use common\models\Common;
use common\components\StudentComponent;
use common\models\Question;
use common\models\AskAginQuestionsLog;
class StudentController extends Controller {

   /**
     * ask-question
     * 
     */
    public function actionAskQuestion() {
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
        
            $model = new \common\models\Question();
           
                    if ($data = $model->askquestion($post,'',0)) {
                        if($data == 1){
                        $result['status'] = 1;
                        $result['message'] = Yii::t('app', 'Question posted successfully');
                        }
                        else{
                        $result['status'] = 1;
                        $result['message'] = Yii::t('app', $data);   
                        }
                    } else {
                        $result['status'] = 0;
                        $result['message'] = Yii::t('app', 'Question not posted.');
                    }
        
        return $result;
    }



    /**
     * Get tutor list list
     */
    public function actionGetFavTutorList() {
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
        
            $snl = new StudentComponent();
            $data = $snl->fav_tutor($post['user_id']);
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'Favourite tutor list.');
            $result['data'] = $data;
        
        return $result;
    }
    
    /**
     * Get tutor list list
     */
    public function actionAddFavTutor() {
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
        
            $snl = new StudentComponent();
            $data = $snl->fav_tutor($post['user_id']);
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'Favourite tutor list.');
            $result['data'] = $data;
        
        return $result;
    }    
    
    /**
     * Get tutor list list
     */
    public function actionGetRecommendedTutorList() {
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
        
            $snl = new StudentComponent();
            $data = $snl->recommended_tutor($post['user_id'],$post['subject_id'],$post['page']);
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'Recommended tutor list.');
            $result['data'] = $data;
        
        return $result;
    }
    
    /**
     * Get tutor list list
     * $view => 0:list,1:detail
     * $question_type => 1:active,2:completed,3:expired
     */
    public function actionMyQuestions() {
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
            
            $filter = [
                'price_type'=>(isset($post['price_type'])&& $post['price_type']!='')? $post['price_type']:'',
                'budget_range'=>(isset($post['budget_range'])&& $post['budget_range']!='')?$post['budget_range']:'',
                'qualification_id'=>(isset($post['qualification_id'])&& $post['qualification_id']!='')?$post['qualification_id']:'',
                'subject_ids'=>(isset($post['subject_ids'])&& $post['subject_ids']!='')?$post['subject_ids']:'',
                'is_priority_set'=>(isset($post['is_priority_set'])&& $post['is_priority_set']!='')?$post['is_priority_set']:'',
                'dateorder'=>(isset($post['dateorder'])&& $post['dateorder']!='')? $post['dateorder']:2,
                'confirm_select_tutor'=>(isset($post['confirm_select_tutor'])&& $post['confirm_select_tutor']!='')?$post['confirm_select_tutor']:0,
                
            ]; 
            $snl = new StudentComponent();
            $data = $snl->student_questions($post['user_id'],$post['page'],$post['question_type'],$post['view'],(isset($post['question_id']))?$post['question_id']:0,$filter);
            
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'My questions list.');
            $result['data'] = $data;
        
        return $result;
    }
    
    /**
     * Get tutor profile
     * user_id (tutor_id),student_id
     */
    public function actionTutorProfile() {
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
       
            $snl = new StudentComponent();
            $data = $snl->get_tutor_profile($_POST['tutor_id'], $_POST['user_id']);
            
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'Tutor profile.');
            $result['data'] = $data;
        
        return $result;
    }
/**
     * Delete expired questions
     * question_id
     */
    public function actionDeleteQuestions() {
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
        $questions = $post['question_ids'];
        $sql_questions = "UPDATE `question` SET `status`=2 WHERE question_id IN ($questions)";
        $data_questions = yii::$app->db->createCommand($sql_questions)->execute();
        $sql_invited_tutor = "UPDATE `invited_tutor` SET `status`=2 WHERE question_id IN ($questions)";
        $data_invited_tutor = yii::$app->db->createCommand($sql_invited_tutor)->execute();
            
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'Questions(s) deleted successfully.');
        
        return $result;
    }
    
/**
     * Delete invited tutor
     * question_id,tutor_id,user_id
     */
    public function actionDeleteInvitedTutor() {
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
        $sql_invited_tutor = "UPDATE `invited_tutor` SET `status`=2 
                              WHERE question_id = ".$post['question_id']." AND tutor_id = ".$post['tutor_id']." ";
        $data_invited_tutor = yii::$app->db->createCommand($sql_invited_tutor)->execute();
            
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'Tutor deleted successfully.');
        
        return $result;
    }    
 
/**
     * Delete invited tutor
     * service_token,user_id,tutor_id
     */
    public function actionAddFavouriteTutor() {
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
       
            $snl = new StudentComponent();
            if($data = $snl->add_fav_tutor($_POST['user_id'], $_POST['tutor_id'])){
                if($data == 1)
                {
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'Tutor added as favourite.');
                }
                if($data == 2)
                {
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'Tutor removed from favourite.');
                }
            }
            
        
        return $result;
    }       
/**
     * ask expired question again
     * service_token,user_id,tutor_id
     * QUERY : pushnotifications to tutors?
     */
    public function actionAskQuestionAgain() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $result = [];
        $data = [];
        $gnl = new GeneralComponent();
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
       
        $question = Question::findOne($post['question_id']);
        $question->asked_date=date("Y-m-d H:i:s");
        $question->confirm_bid=0;
        $question->bid_status=0;
        $question->confirm_select_tutor=0;
        $question->answer_id=0;
        $question->mark_completed_student=0;
        $question->mark_completed_tutor=0;
        $question->question_status=1;
        if($question->save(FALSE))
        {
            $adata = array();
            $adata['tutor_requst_status'] = 0;
            $adata['is_confirmed'] = 0;
            $question_id = $post['question_id'];
            \Yii::$app->db->createCommand()->update('invited_tutor', $adata, "question_id=$question_id")->execute();
            
            $bdata = array();
            $bdata['status'] = 2;
            $bdata['question_id'] = $post['question_id'];
            \Yii::$app->db->createCommand()->update('chat', $bdata, "question_id=$question_id")->execute();
            
            /*$rdata = array();
            $rdata['status'] = 2;
            $rdata = $post['question_id'];
            \Yii::$app->db->createCommand()->update('review', $rdata, "question_id=$question_id")->execute();
            \Yii::$app->db->createCommand()->update('report_abuse', $rdata, "question_id=$question_id")->execute();
             * 
             */
            
            $ask_again = new AskAginQuestionsLog();
            $ask_again->question_id=$post['question_id'];
            $ask_again->asked_date=date("Y-m-d H:i:s");
            $ask_again->save(FALSE);
            
            $tutors = \common\models\InvitedTutor::find()->where(['question_id'=>$question_id])->asArray()->all();
            $tutor_array=[];
            $i = 0;
            foreach ($tutors as $value) {
                    $tutor_array[$i] = $value['tutor_id'];
                    $i++;
                }
            foreach ($tutor_array as $tutor_id) { 
                 
                $sender = User::findOne($question['created_by']);
                $receiver = User::findOne($tutor_id);
//              -------------------------------------N_PUSHNOTIFICATION11-START---------------------------------------
                        $push_noti_msg = $sender['first_name']." ".$sender['last_name']." has invited you to answer a question.";
                        $noti_type = 11;
                        $data = [];
//                        if ($receiver->is_notification == 1) {
                            $param = ["message" => $push_noti_msg, "type" => $noti_type, "data" => $data];
                            if ($receiver->device_type == 'ios') {
                                NotificationComponent::send_push($receiver->device_token, $param, "ios");
                            }
                            if ($receiver->device_type == 'android') {
                                NotificationComponent::send_push($receiver->device_token, $param, "android");
                            }
//                        }
                        GeneralComponent::saveNotificationLog($question_id, $sender['id'], $receiver['id'], $noti_type, $push_noti_msg, $sender['id']);
//            -------------------------------------PUSHNOTIFICATION-END---------------------------------------
            
            //----------------N_EMAILNOTIFICATION-------------mail-to-tutor-start--------------------------------------------
            if($receiver['email_on_selection'] == 1){
            $email_model = \common\models\EmailTemplate::findOne(5);
            $subject = $email_model->emailtemplate_subject;
                            $bodymessage = $email_model->emailtemplate_body;
                            $bodymessage = str_replace('{tutor}', $receiver->first_name.' '.$receiver->last_name, $bodymessage);
                            $bodymessage = str_replace('{student}', $sender->first_name.' '.$sender->last_name, $bodymessage);
            $gnl->sendMail($receiver->email, $subject, $bodymessage);
            }
            //-----------------------------mail-to-tutor-end--------------------------------------------
        }
            
            
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'Question asked again.');
        }
            
        
        return $result;
    }    
    
/**
     * Delete invited tutor
     * service_token,user_id,question_id,hour
     */
    public function actionExtendTimelimit() {
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
       
            $snl = new StudentComponent();
            if($data = $snl->extend_time_limit($_POST['question_id'],$_POST['user_id'], $_POST['hour'])){
                if($data == 1)
                {
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'Success.');
                }
                if($data == 2)
                {
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'You can not extend timelimit now.');
                }
            }
            
        
        return $result;
    }      
/**
     * review for tutor
     * service_token,user_id,tutor_id,review_opt,comment,rating,question_id
     */
    public function actionAddTutorReview() {
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
        $review = Review::find()->where(['question_id'=>$post['question_id'],'posted_by'=>$post['user_id'],'posted_for'=>$post['tutor_id'],'status'=>1])->one();
        if(empty($review)){
            $user_by = User::findOne($post['user_id']);
            $user_for = User::findOne($post['tutor_id']);
            $model = new Review();
            $model->review_opt=$post['review_opt'];
            $model->comment=$post['comment'];
            $model->rating=$post['rating'];
            $model->posted_by=$post['user_id'];
            $model->posted_for=$post['tutor_id'];
            $model->question_id=$post['question_id'];
            $model->created_date=date("Y-m-d H:i:s");
            $model->posted_by_role=$user_by['role'];
            $model->posted_for_role=$user_for['role'];
            $model->save(FALSE);
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'Review added.');}
        else {
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'Already added.');
       }
       
            
        
        return $result;
    }  
    
    /**
     * student rejects tutor
     * service_token,user_id(student),tutor_id,question_id
     */
public function actionStudentRejectTutor() {
        
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
       
       if(isset($post['tutor_id']) && isset($post['user_id']) && isset($post['question_id']))
       {
        $snl = new StudentComponent();
            if($data = $snl->reject_tutor($post['user_id'], $post['tutor_id'],$post['question_id'])){
                if($data == 1){
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'Tutor rejected successfully.');
                }
                if($data == 2){
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'This tutor has not confirmed your question.');
                }
                if($data == 3){
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'This question is expired or deleted by student.');
                }
            }
            else {
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'This tutor has not confirmed your question.');
            }
       }
       else
       {
           $result['status'] = 0;
           $result['message'] = Yii::t('app', 'Invalid Parameters.');
       }    
       return $result;  
    }
    
   /**
     * student accepts tutor
     * service_token,user_id(student),tutor_id,question_id
     */ 
    
    public function actionStudentAcceptTutor() {
        
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
       
       if(isset($post['tutor_id']) && isset($post['user_id']) && isset($post['question_id']))
       {
        $snl = new StudentComponent();
            if($data = $snl->accept_tutor($post['user_id'], $post['tutor_id'],$post['question_id'])){
                if($data == 1){
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'Tutor accepted successfully.');
                }
                if($data == 2){
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'This tutor has not confirmed your question.');
                }
                if($data == 3){
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'This question is expired or deleted by student.');
                }
            }
            else {
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'This tutor has not confirmed your question.');
            }
       }
       else
       {
           $result['status'] = 0;
           $result['message'] = Yii::t('app', 'Invalid Parameters.');
       }    
       return $result;
    }

 
    public function actionGetUserCard() {
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
            
            
            $card_detail = \Yii::$app->db->createCommand("SELECT * FROM student_card_detail Where status = 1 AND user_id=".$post['user_id'])->queryAll();
            
            if($card_detail)
            {
                foreach ($card_detail as $value) {
                    $data[] = [
                            "user_id"=> (int)$value['user_id'],
                            "stripe_card_id"=> (string)$value['stripe_card_id'],
                            "brand"=> (string)$value['brand'],
                            "country"=> (string)$value['country'],
                            "stripe_customer_id"=> (string)$value['stripe_customer_id'],
                            "card_holder_name"=> (string)$value['card_holder_name'],
                            "cvc_check"=> (string)$value['cvc_check'],
                            "exp_month"=>(int) $value['exp_month'],
                            "exp_year"=> (int)$value['exp_year'],
                            "fingerprint"=> (string)$value['fingerprint'],
                            "funding"=> (string)$value['funding'],
                            "last_four"=> (int)$value['last_four'],
                            "created_date"=> (string)$value['created_date'],
                            ];
                }  
                
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'success.');
                $result['data'] = $data;
            
            }   
            else
            {
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'No card found.');
                $result['data'] = $data;
            }    
         
            
        
        return $result;
    }
  
    
    
    public function actionAddUserCard() {
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
            $userobj = new User();
            
            $carddetail = array('number' => $post['card_number'],
                                'object'=>'card',
                                'card_holder_name'=>$post['card_holder_name'],
                                'exp_month' => $post['expiry_month'],
                                'exp_year' => $post['expiry_year'], 
                                'cvc' => $post['cvv']);
            $carddetail_detail_response =  $userobj->stripe_add_cart_to_customer($post['user_id'],$carddetail);
            if($carddetail_detail_response['status'])
            {
                $card_detail = \Yii::$app->db->createCommand("SELECT * FROM student_card_detail 
                                                                       Where status = 1 AND 
                                                                       stripe_card_id = '".$carddetail_detail_response['stripe_data']['stripe_card_id']."' AND
                                                                       user_id=".$post['user_id'])->queryOne();
                $data = [
                            "user_id"=> (int)$card_detail['user_id'],
                            "stripe_card_id"=> (string)$card_detail['stripe_card_id'],
                            "brand"=> (string)$card_detail['brand'],
                            "country"=> (string)$card_detail['country'],
                            "stripe_customer_id"=> (string)$card_detail['stripe_customer_id'],
                            "card_holder_name"=> (string)$card_detail['card_holder_name'],
                            "cvc_check"=> (string)$card_detail['cvc_check'],
                            "exp_month"=>(int) $card_detail['exp_month'],
                            "exp_year"=> (int)$card_detail['exp_year'],
                            "fingerprint"=> (string)$card_detail['fingerprint'],
                            "funding"=> (string)$card_detail['funding'],
                            "last_four"=> (int)$card_detail['last_four'],
                            "created_date"=> (string)$card_detail['created_date'],
                        ];
                
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'Card added successfully.');
                $result['data'] = $data;
            }   
            else
            {
                $result['status'] = 0;
                $result['message'] = Yii::t('app', $carddetail_detail_response['message']);
            }    
         
            
        
        return $result;
    }
    
   
    public function actionPayment()
    {
        $user_id = $_REQUEST['user_id'];
        $page = $_REQUEST['page'];
        $start = PAGE_SIZE * ($page - 1);
        
        if($page > 0)
        {
            $page_condition = " LIMIT " . $start . ", " . PAGE_SIZE;
        }
        else
        {
            $page_condition = ""; 
        }
        
        
        if(@$_REQUEST['dateorder'] == 1)
        {
            $orderby = 't.created_date ASC';
        }   
        else if(@$_REQUEST['dateorder'] == 2)
        {
            $orderby = 't.created_date DESC';
        }    
        else
        {
            $orderby = 't.created_date DESC';
            
        }   
        
        
        $where = "";
        if($_REQUEST['user_id'])
        {
            $where .=" AND t.user_id = $user_id"; 
        } 
        
          
        if(@$_REQUEST['range_min_max'])
        {
                         $range_array = explode(',', trim($_REQUEST['range_min_max']));
                         
                         $where .= " AND (amount BETWEEN $range_array[0] AND $range_array[1] )";
                         
        }    
                
        if(@$_REQUEST['from_date'] AND @$_REQUEST['to_date'])
        {    
                $fromdate = date('Y-m-d',strtotime($_REQUEST['from_date']));
                $todate = date('Y-m-d',strtotime($_REQUEST['to_date']));
                $where .= " AND DATE(t.created_date) >='$fromdate' AND DATE(t.created_date) <= '$todate'";
        }
        
        if(@$_REQUEST['export']==1)
        {
            $page_condition = "";
        }
        
        $query  = "SELECT t.*,q.title,qf.name as qualification_name,s.name as subject_name FROM transaction t  
                   LEFT JOIN question q ON t.question_id = q.question_id
                   LEFT JOIN qualification qf ON qf.qualification_id = q.qualification_id
                   LEFT JOIN subject s ON s.subject_id = q.subject_id
                   WHERE t.status != 2 $where ORDER BY $orderby $page_condition";
        
        $query_result = \Yii::$app->db->createCommand($query)->queryAll();
        
        $query  = "SELECT COUNT(*) FROM transaction t WHERE t.status != 2 $where";
        $total_records = \Yii::$app->db->createCommand($query)->queryScalar();
        
        if($query_result)
        {
            $transaction_detail = array();
            $i = 0;
            foreach ($query_result as $value) {
                $transaction_detail[$i]['studypad_txn_id'] = $value['studypad_txn_id'];
                $transaction_detail[$i]['title'] = (string)$value['title'];
                $transaction_detail[$i]['qualification_name'] = (string)$value['qualification_name'];
                $transaction_detail[$i]['subject_name'] = (string)$value['subject_name'];
                $transaction_detail[$i]['question_id'] = (integer)$value['question_id'];
                $transaction_detail[$i]['created_date'] = (string)\common\components\GeneralComponent::date_format($value['created_date']);
                $transaction_detail[$i]['price'] = (string)\common\components\GeneralComponent::front_priceformat($value['amount']);
                $i++;
            }
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'success.');
            $result['data'] = $transaction_detail;
            $result['total_records'] = $total_records;
            $result['page_size'] = PAGE_SIZE;
            $result['page_no'] = $page;
        } 
        else
        {
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'No record found.');
            $result['data'] = array();
        }    
        
        
        if(@$_REQUEST['export']==1)
        {
        
            $filename = 'payment-transactions_' .  date('m/d/Y').'.csv';
            $fp = fopen('php://output', 'w');
            ob_clean();
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename='.$filename);
            fputcsv($fp, ['Question Title','Qualification','Subject','Date','Price','Transaction ID']);
            if($transaction_detail)
            {
                foreach ($transaction_detail as $value) 
                {
                    $csvdata = array(   
                                        $value['title'],
                                        $value['qualification_name'],
                                        $value['subject_name'],
                                        $value['created_date'],
                                        $value['price'],
                                        $value['studypad_txn_id'],
                                    );
                    fputcsv($fp, $csvdata);
                }
            }
            fclose($fp);
            exit;
        }
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $result;
    }      
        
}
