<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\db\Query;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;
//use frostealth\yii2\aws\s3\interfaces\Service;
use yii\helpers\Json;
use iisns\markdown\Markdown;
use common\models\User;
use common\models\FavTutor;
use common\models\Question;
use common\models\QuestionDocument;
use yii\helpers\ArrayHelper;
use common\components\StudentComponent;
use common\components\TutorComponent;
use common\models\Chat;
use common\models\ReportOption;
use common\models\ReviewOption;
class MasterComponent extends Component {

   public function question_detail($question_id,$user_id)
    {
       
         $gnl = new GeneralComponent();
         $snl = new StudentComponent();
         $tnl = new TutorComponent();
         $user_info = [];
         $role = User::find()->where(['id'=>$user_id,'status'=>1])->one();
         if(empty($role))
         {
             return FALSE;
         }
         if($role['role'] == 'student')
                   {
         $question_sql = "SELECT question.*,
                         (SELECT name FROM qualification where qualification.qualification_id = question.qualification_id) as qualification_name,
                         (SELECT name FROM subject where subject.subject_id = question.subject_id) as subject_name,
                         IF((SELECT review_id FROM review WHERE question_id = $question_id AND posted_by = question.confirm_select_tutor AND posted_for = question.created_by LIMIT 1), 1, 0) as tutor_reviewed,
                         IF((SELECT review_id FROM review WHERE question_id = $question_id AND posted_for = question.confirm_select_tutor AND posted_by = question.created_by LIMIT 1), 1, 0) as student_reviewed
                         FROM question WHERE  `question_id` = $question_id AND status = 1 " ;
                   }
         if($role['role'] == 'tutor')
                   {
          $question_sql = "SELECT question.*,invited_tutor.is_confirmed,invited_tutor.tutor_requst_status,invited_tutor.tutor_bid_amount,
                         (SELECT name FROM qualification where qualification.qualification_id = question.qualification_id) as qualification_name,
                         (SELECT name FROM subject where subject.subject_id = question.subject_id) as subject_name,
                         IF((SELECT review_id FROM review WHERE question_id = $question_id AND posted_by = question.confirm_select_tutor AND posted_for = question.created_by LIMIT 1), 1, 0) as tutor_reviewed,
                         IF((SELECT review_id FROM review WHERE question_id = $question_id AND posted_for = question.confirm_select_tutor AND posted_by = question.created_by LIMIT 1), 1, 0) as student_reviewed
                         FROM question 
                         LEFT JOIN invited_tutor ON question.question_id = invited_tutor.question_id 
                         WHERE  `question`.`question_id` = $question_id AND question.status = 1 AND invited_tutor.tutor_id = $user_id 
                         AND invited_tutor.tutor_requst_status IN (0,1) AND invited_tutor.status = 1 " ;
                   } 
         $question = yii::$app->db->createCommand($question_sql)->queryOne(); 
//         print_r($question);exit;
         if(!empty($question)){
                   $answer = [];$awaiting_student_confirmation == 0;
                   $tutor_details = $all_tutor_details  = array();
                   if($role['role'] == 'student')
                   {
                   if($question['confirm_select_tutor'] == 0)  {
                     $user_info =   $snl->get_tutor_for_question($question['question_id'],$user_id,1) ;
                   }
                   else if($question['confirm_select_tutor'] != 0){
                       $user_info[] = $snl->get_tutor_profile($question['confirm_select_tutor'], $question['created_by'],$question['question_id']);
                   }
                   }
                   if($role['role'] == 'tutor')
                   {
                   $student_details[] = $tnl->get_student_profile($question['created_by'],1);
                   $user_info = $student_details;
                   if($question['is_confirmed'] == 0 && $question['tutor_requst_status'] == 1){
                       $awaiting_student_confirmation = 1;
                   }
                   else if($question['is_confirmed'] == 2){
                       $awaiting_student_confirmation = 2;
                   }
                   }
                   
                   if( $question['confirm_select_tutor'] != 0) 
                   {
                       $answer_statement = Chat::find()->where(['chat_id'=>$question['answer_id'],'accepted_answer_student'=>[0,1]])->One();
                       if(!empty($answer_statement) ){
                         $answer = [
                             "answer_id"=>(int)$answer_statement['chat_id'],
                             "answer_type"=>(int)$answer_statement['message_type'],
                             "answer_text"=>(string)$answer_statement['message'],
                             "file_name" => (string)  $gnl->image_not_found_api_main('chat_files', $answer_statement['file_name']) ,
                             "file_name_original" =>  (string) $answer_statement['file_original_name'] ,
                         ];  
                       }
                   }
                   $is_tutor_confirmed = 0;
                   if($question['confirm_select_tutor'] != 0){$is_tutor_confirmed = 1;}
                   
                   if($question['question_status'] == 2){
                     $mnl = new MasterComponent();
                     $payment = $mnl->payment_detail($question['question_id']);
                   }
                   
                   $tutor_review = $student_review = [];
                   if($question['tutor_reviewed'] == 1){
                       $reviews = \common\models\Review::find()
                               ->where(['question_id'=>$question['question_id'],'posted_by'=>$question['confirm_select_tutor'],'posted_for'=>$question['created_by'],'status'=>1])->asArray()->one();
                       $tutor_review = [
                           'review_opt'=>$reviews['review_opt'],
                           'comment'=>$reviews['comment'],    
                           'rating'=>$reviews['rating'],   
                           'created_date'=>$reviews['created_date'], 
                       ];
                   }
                   if($question['student_reviewed'] == 1){
                       $reviews = \common\models\Review::find()
                               ->where(['question_id'=>$question['question_id'],'posted_by'=>$question['created_by'],'posted_for'=>$question['confirm_select_tutor'],'status'=>1])->asArray()->one();
                       $student_review = [
                           'review_opt'=>$reviews['review_opt'],
                           'comment'=>$reviews['comment'],    
                           'rating'=>$reviews['rating'],    
                           'created_date'=>$reviews['created_date'],    
                       ];
                   }
                   
                   
                   $questions[] = [
                             'question_id'=>(int) $question['question_id'],
                             'title'=>(string) $question['title'],
                             'description'=>(string) $question['description'],
                             'time_limit'=>(int) $question['time_limit'],
                             'is_priority_set'=>(int) $question['is_priority_set'],
                             'qualification_id'=>(int) $question['qualification_id'],
                             'qualification_name'=>(string) $question['qualification_name'],
                             'subject_id'=>(int) $question['subject_id'],
                             'subject_name'=>(string) $question['subject_name'],
                             'price_type'=>(int) $question['price_type'],
                             'price'=>(string) $question['price'],
                             'min_budget'=>(string) $question['min_budget'],
                             'max_budget'=>(string) $question['max_budget'],
                             //'confirm_bid'=>(string) $question['confirm_bid'],
                             'bid_status'=>(int) $question['bid_status'],
                             'asked_date'=>(string) strtotime($question['asked_date']),
                             'current_date'=>(string) time(),
                             'completed_date'=>($question['question_status']==2)? (string) strtotime($question['completed_date']):"",
                             'documents'=> $snl->get_question_docs($question['question_id']),
                             'is_tutor_confirmed'=> (int) $is_tutor_confirmed,
                             'confirmed_tutor_id'=> (int) $question['confirm_select_tutor'],
                             'answer'=>  $answer,
                             'payment_type' => (string)$payment['payment_type'],
                             'payment_amount' => (int)$payment['amount'],
                             'user_info' => $user_info,
                       
                             'student_id'=>(int) $question['created_by'],
                             'tutor_requst_status' => (int) $question['tutor_requst_status'],
                             'awaiting_student_confirmation' => (int) $awaiting_student_confirmation,//0:do not show,1:pending,2:rejected
                             'is_tutor_confirmed'=> (int) $is_tutor_confirmed,
                             'confirmed_tutor_id'=> (int) $question['confirm_select_tutor'],
                             'student_confirmation_status'=> (int) $question['is_confirmed'],
                       
                             'question_status'=>(int) $question['question_status'],
                             'tutor_reviewed' => (int) $question['tutor_reviewed'],
                             'student_reviewed' => (int) $question['student_reviewed'],
                             'tutor_review_for_student' => $tutor_review,//webservice
                             'student_review_for_tutor' => $student_review,//webservice
                           ]; 
                 
             
         }
         else{
                    $questions = [];
         }
                $data['questions'] = $questions;
         return $data;
    }  
    /*
     * question status after student confirmed
     */
    
    public function question_status($question_id,$user_id)
    {
         $gnl = new GeneralComponent();
         $snl = new StudentComponent();
         $tnl = new TutorComponent();
         $role = User::find()->where(['id'=>$user_id,'status'=>1])->one();
         if(empty($role))
         {
             return FALSE;
         }
         if($role['role'] == 'student')
                   {
         $question_sql = "SELECT question.*,
                         IF((SELECT chat_id FROM chat WHERE question_id = $question_id AND accepted_answer_student = 1 LIMIT 1), 1, 0) as mark_completed_student,
                         IF((SELECT chat_id FROM chat WHERE question_id = $question_id AND marked_answer_tutor = 1 LIMIT 1), 1, 0) as mark_completed_tutor,
                         IF((SELECT review_id FROM review WHERE question_id = $question_id AND posted_by = question.confirm_select_tutor AND posted_for = question.created_by LIMIT 1), 1, 0) as tutor_reviewed,
                         IF((SELECT review_id FROM review WHERE question_id = $question_id AND posted_for = question.confirm_select_tutor AND posted_by = question.created_by LIMIT 1), 1, 0) as student_reviewed
                         FROM question WHERE `confirm_select_tutor` != 0 AND `question_id` = $question_id  AND status = 1 
                         " ;
                   }
         if($role['role'] == 'tutor')
                   {
          $question_sql = "SELECT question.*,invited_tutor.is_confirmed,invited_tutor.tutor_requst_status,invited_tutor.tutor_bid_amount,
                         IF((SELECT chat_id FROM chat WHERE question_id = $question_id AND accepted_answer_student = 1 LIMIT 1), 1, 0) as accepted_answer_student,
                         IF((SELECT chat_id FROM chat WHERE question_id = $question_id AND marked_answer_tutor = 1 LIMIT 1), 1, 0) as mark_completed_tutor,
                         IF((SELECT review_id FROM review WHERE question_id = $question_id AND posted_by = question.confirm_select_tutor AND posted_for = question.created_by LIMIT 1), 1, 0) as tutor_reviewed,
                         IF((SELECT review_id FROM review WHERE question_id = $question_id AND posted_for = question.confirm_select_tutor AND posted_by = question.created_by LIMIT 1), 1, 0) as student_reviewed
                         FROM question 
                         LEFT JOIN invited_tutor ON question.question_id = invited_tutor.question_id 
                         WHERE  `confirm_select_tutor` != 0 AND `question`.`question_id` = $question_id 
                         AND question.status = 1 AND invited_tutor.tutor_id = $user_id  
                         AND invited_tutor.tutor_requst_status IN (0,1) AND invited_tutor.status = 1" ;
                   } 
         $question = yii::$app->db->createCommand($question_sql)->queryOne(); 
//         print_r($question);exit;
         if(!empty($question)){
                   $answer = [];$awaiting_student_confirmation == 0;
                   $tutor_details = $all_tutor_details  = (object)array();
                   if($role['role'] == 'student')
                   {
                   $all_tutor_details = ( $question['confirm_select_tutor'] == 0) ? $snl->get_tutor_for_question($question['question_id'],$user_id,1) : (object)array();
                   $tutor_details = ($question['confirm_select_tutor'] != 0) ? $snl->get_tutor_profile($question['confirm_select_tutor'], $question['created_by'],$question['question_id']) : $all_tutor_details;//if confirm_select_tutor == 0 then confirmed tutor else all;
                   $user_info = $tutor_details;
                   }
                   if($role['role'] == 'tutor')
                   {
                   $student_details[] = $tnl->get_student_profile($question['created_by'],1);
                   $user_info = $student_details;
                   if($question['is_confirmed'] == 0 && $question['tutor_requst_status'] == 1){
                       $awaiting_student_confirmation = 1;
                   }
                   else if($question['is_confirmed'] == 2){
                       $awaiting_student_confirmation = 2;
                   }
                   }
                   
                   if( $question['confirm_select_tutor'] != 0) 
                   {
                       $answer_statement = Chat::find()->where(['chat_id'=>$question['answer_id'],'accepted_answer_student'=>[0,1]])->One();
                       if(!empty($answer_statement) ){
                         $answer = [
                             "answer_id"=>(int)$answer_statement['chat_id'],
                             "answer_type"=>(int)$answer_statement['message_type'],
                             "answer_text"=>(string)$answer_statement['message'],
                             "file_name" => (string)  $gnl->image_not_found_api_main('chat_files', $answer_statement['file_name']) ,
                             "file_name_original" =>  (string) $answer_statement['file_original_name'] ,
                         ];  
                       }
                   }
                   $is_tutor_confirmed = 0;$tutor_review = $student_review = [];
                   if($question['tutor_reviewed'] == 1){
                       $reviews = \common\models\Review::find()
                               ->where(['question_id'=>$question['question_id'],'posted_by'=>$question['confirm_select_tutor'],'posted_for'=>$question['created_by'],'status'=>1])->asArray()->one();
                       $tutor_review = [
                           'review_opt'=>$reviews['review_opt'],
                           'comment'=>$reviews['comment'],    
                           'rating'=>$reviews['rating'],   
                           'created_date'=>$reviews['created_date'], 
                       ];
                   }
                   if($question['student_reviewed'] == 1){
                       $reviews = \common\models\Review::find()
                               ->where(['question_id'=>$question['question_id'],'posted_by'=>$question['created_by'],'posted_for'=>$question['confirm_select_tutor'],'status'=>1])->asArray()->one();
                       $student_review = [
                           'review_opt'=>$reviews['review_opt'],
                           'comment'=>$reviews['comment'],    
                           'rating'=>$reviews['rating'],    
                           'created_date'=>$reviews['created_date'],    
                       ];
                   }
                   if($question['confirm_select_tutor'] != 0){$is_tutor_confirmed = 1;}
                   $questions = [
                             'question_id'=>(int) $question['question_id'],
                             'question_status'=>(int) $question['question_status'],
                             'accepted_answer_student' => (int) $question['accepted_answer_student'],
                             'mark_completed_student'=>(int) $question['mark_completed_student'],
                             'mark_completed_tutor'=>(int) $question['mark_completed_tutor'],
                             'answer_id'=>(int) $question['answer_id'],
                             'answer'=>  $answer,
                             'tutor_reviewed' => (int) $question['tutor_reviewed'],
                             'student_reviewed' => (int) $question['student_reviewed'],
                             'tutor_review' => $tutor_review,
                             'student_review' => $student_review,
                       
                           ]; 
                 
             
         }
         else{
                    $questions = [];
         }
         return $questions;
    } 
    
    /*
     * tutors answers
     */
    
    public function tutor_answer($question_id)
    {
         $gnl = new GeneralComponent();
         $question = Question::find()->where(['question_id'=>$question_id,'status'=>1])->one(); 
         $chat_sql = "SELECT chat.*,
                      (SELECT profile_photo FROM user where user.id = chat.sender_id) as tutor_profile_photo
                         FROM chat WHERE chat.`question_id` = $question_id AND chat.status = 1 AND owner_del = 0
                         AND chat.sender_id = ".$question['confirm_select_tutor']." ORDER BY chat_id ASC" ;
         
         $chat_data = yii::$app->db->createCommand($chat_sql)->queryAll(); 
         foreach ($chat_data as $value) {
             $chat[] = [
                        "chat_id" => (int) $value["chat_id"],
                        "sender_id" => (int) $value["sender_id"],
                        "receiver_id" => (int) $value["receiver_id"],
                        "tutor_profile_photo_thumb" => (string) $gnl->image_not_found_api_thumb('profile_photo', $value["tutor_profile_photo"]) ,
                        "message" => (string) $value["message"],
                        "message_type" => (int) $value["message_type"],
                        "file_name" => (string)  $gnl->image_not_found_api_main('chat_files', $value['file_name']) ,
                        "file_name_thumb" => (string)  $gnl->image_not_found_api_thumb('chat_files', $value['file_name']) ,
                        "file_name_original" =>  (string) $value['file_original_name'] ,
                        "created_date" => (double) strtotime($value["created_date"]),
                    ];
         }
//         echo '<pre>';print_r($chat);exit;
         return $chat;
    } 
     
    public function get_report_option_service($user_id)
    {
        $data = [];
        $user = User::find()->where(['id'=>$user_id,'status'=>1])->asArray()->one();
        if(isset($user['role'])){
            if($user['role'] == 'student'){
                 $issues = ReportOption::find()->where(['role'=>'tutor','status'=>1])->asArray()->all();
                foreach ($issues as $issue) {
                    $data[] = [
                        'id' =>(int) $issue['report_option_id'],
                        'value' => $issue['option'],
                    ];
                }
            }
            else if($user['role'] == 'tutor'){
                $issues = ReportOption::find()->where(['role'=>'student','status'=>1])->asArray()->all();
                foreach ($issues as $issue) {
                    $data[] = [
                        'id' => (int)$issue['report_option_id'],
                        'value' => $issue['option'],
                    ];
                }
            }
        }
       return $data; 
    }
    public function get_review_option_service($user_id)
    {
        $data = [];
        $user = User::find()->where(['id'=>$user_id,'status'=>1])->asArray()->one();
        if(isset($user['role'])){
            if($user['role'] == 'student'){
                $issues = ReviewOption::find()->where(['role'=>'student','status'=>1])->asArray()->all();
                foreach ($issues as $issue) {
                    $data[] = [
                        'id' => (int)$issue['review_option_id'],
                        'value' => $issue['option'],
                    ];
                }
            }
            else if($user['role'] == 'tutor'){
                $issues = ReviewOption::find()->where(['role'=>'tutor','status'=>1])->asArray()->all();
                foreach ($issues as $issue) {
                    $data[] = [
                        'id' =>(int) $issue['review_option_id'],
                        'value' => $issue['option'],
                    ];
                }
            }
        }
       return $data; 
    }
    public function get_report_option($role)
    {
        $data = [];
            if($role == 'student'){
                 $issues = ReportOption::find()->where(['role'=>'student','status'=>1])->asArray()->all();
                foreach ($issues as $issue) {
                $data[$issue['report_option_id']] = $issue['option'];
                }
            }
            else if($role == 'tutor'){
                $issues = ReportOption::find()->where(['role'=>'tutor','status'=>1])->asArray()->all();
                foreach ($issues as $issue) {
                $data[$issue['report_option_id']] = $issue['option'];
                }
            }
       return $data; 
    }
    /*
     * get review options for whom review is given
     */
    public function get_review_option($role)
    {
        $data = [];
            if($role == 'student'){
                $issues = ReviewOption::find()->where(['role'=>'student','status'=>1])->asArray()->all();
                foreach ($issues as $issue) {
                $data[$issue['review_option_id']] = $issue['option'];
                }
            }
            else if($role == 'tutor'){
                $issues = ReviewOption::find()->where(['role'=>'tutor','status'=>1])->asArray()->all();
                foreach ($issues as $issue) {
                $data[$issue['review_option_id']] = $issue['option'];
                }
            }
        
       return $data; 
    }
    
    public function payment_detail($question_id)
    {
        $data = [];
           $payment_sql = 'SELECT transaction.amount,
                                    (SELECT funding FROM student_card_detail WHERE student_card_detail.stripe_card_id = transaction.stripe_card_id) as payment_type
                                     FROM transaction WHERE question_id = '.$question_id.'';
           $payment = yii::$app->db->createCommand($payment_sql)->queryOne(); 
            $data = [
                'payment_type'=>(string)$payment['payment_type'],
                'payment_amount'=>(int)$payment['amount'],
            ];
       return $data; 
    }
    
    public function notifications($user_id,$page)
    {
       
        $notifi_data = (object)array();
        $gnl = new GeneralComponent();
        $page_condition = "";$data = [];
        if($page > 0)
         {
            $start = PAGE_SIZE * ($page - 1);
            $page_condition = " LIMIT " . $start . ", " . PAGE_SIZE;
         }
         $noti_data = Yii::$app->db->createCommand("
                            SELECT ng.*,
                            (SELECT CONCAT(first_name,' ',last_name) from user WHERE id=ng.notification_from) as notification_from_name,
                            (SELECT profile_photo from user WHERE id=ng.notification_from) as notification_from_profile_photo
                            FROM notification_generalization as ng
                            WHERE ng.notification_to='" . $user_id . "' and ng.status=0
                            ORDER BY ng.notification_generalization_id DESC
                            " . $page_condition)
                    ->queryAll();
         $unread_notification_count = \common\models\NotificationGeneralization::findAll(['notification_to'=>$user_id,'is_read'=>0]);
         $noti_count = Yii::$app->db->createCommand("
                            SELECT * FROM notification_generalization WHERE notification_to='" . $user_id . "' and status=0" )
                            ->queryAll();
         
         if(!empty($noti_data)){
                foreach ($noti_data as $key => $val) {
                $data[] = [
                        "notification_id" => (int)$val['notification_generalization_id'],
                        "notification_from" => (int)$val['notification_from'],
                        "notification_from_name" => $val['notification_from_name'],
                        "notification_to" => (int)$val['notification_to'],
                        "notification_type" => (int)$val['notification_type'],
                        "notification_text" => (string) $val['notification_text'],
                        "is_read" => (int)$val['is_read'],
                        "profile_photo" => (string)$gnl->image_not_found_api_main('profile_photo', $val['notification_from_profile_photo']),
                        "profile_photo_thumb" => (string)$gnl->image_not_found_api_thumb('profile_photo', $val['notification_from_profile_photo']),
                        "created_date" => strtotime($val["created_date"]),
                        "studypad_id" => (int)$val['studypad_id'],
                        "redirect" => (int)$redirect,
                        ];
                    }
            }
            
            //mark as read
            Yii::$app->db->createCommand("UPDATE notification_generalization set is_read=1 where notification_to=" . $user_id)->execute();
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'status');
            $result['page_no'] = (int) $page;
            $result['unread_notification_count'] = (int) count($unread_notification_count);
            $result['total_records'] = count($noti_count);
            $result['page_limit'] = PAGE_SIZE;
            $result['data'] = $data;
//            $result['data'] = array_slice($data, $start, PAGE_SIZE);
        
       return $result;
    }
    public function     unread_notifications($user_id)
    {
           $unread_notification_count = \common\models\NotificationGeneralization::find()->where(['notification_to'=>$user_id,'is_read'=>0])->asarray()->all();
           return count($unread_notification_count);
    }
    
     public function remove_notification($notification_id) {
        
            Yii::$app->db->createCommand("UPDATE notification_generalization set status=2 where notification_generalization_id=" . $notification_id)->execute();
            return true;
    } 
}
