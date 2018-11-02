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
use common\components\StudentComponent;
use common\models\InvitedTutor;
use common\models\Qualification;
use common\models\Chat;
use common\components\GeneralComponent;
use common\components\NotificationComponent;
class TutorComponent extends Component {

/*
 * $view => 0:list,1:detail
 * $question_type => 1:active,2:completed,3:expired
 * response => 1:mobile,2 : web
 */  
    public function tutor_questions($tutor_id,$page,$question_type,$view,$question_id,$filter,$response)
    {
         $gnl = new GeneralComponent();
         $snl = new StudentComponent();
         $start = PAGE_SIZE * ($page - 1);
         $dateorder = " ORDER BY `asked_date` DESC";
         
         if($page > 0){  $page_condition = " LIMIT " . $start . ", " . PAGE_SIZE;}
         else         {  $page_condition = ""; } 
         if($view == 1){  $view_condition = " AND `question`.`question_id` = $question_id ";}
         else          {  $view_condition = ""; } 
         if($filter['price_type'] != ""){  $price_type_condition = " AND `price_type` = ".$filter['price_type']." ";}
         else          {  $price_type_condition = ""; } 
         if($filter['budget_range'] != ""){  
         list($min_budget, $max_budget) = explode(',', $filter['budget_range']);
         $budget_range_condition = " AND ((min_budget BETWEEN $min_budget AND $max_budget ) OR (max_budget BETWEEN $min_budget AND $max_budget))";}
         else          {  $budget_range_condition = ""; } 
         if($filter['qualification_id'] != ""){  $qualification_condition = " AND `qualification_id` = ".$filter['qualification_id']." ";}
         else          {  $qualification_condition = ""; } 
         if($filter['subject_ids'] != ""){  $subject_condition = " AND `subject_id` IN (".$filter['subject_ids'].")";}
         else          {  $subject_condition = ""; } 
         if($filter['confirm_select_tutor'] == 1){  $confirm_select_tutor = " AND `confirm_select_tutor` <> 0";}
         else          {  $confirm_select_tutor = ""; } 
         if($filter['is_priority_set'] != ""){  $priority_condition = " AND `is_priority_set` = ".$filter['is_priority_set']." ";}
         else          {  $priority_condition = ""; } 
         if($filter['dateorder'] == 1){  $dateorder = "  ORDER BY `asked_date` ASC";}
         
         $filter_condition = $price_type_condition.$budget_range_condition.$qualification_condition.$subject_condition.$priority_condition.$confirm_select_tutor;
         if($filter['qid'] != ''){  $qid = "AND question.question_id = ".$filter['qid']." ";}
         else          {  $qid = ""; } 
         
         
         if($question_type == 1)
         { 
         $question_array_sql = "SELECT * FROM question 
                                LEFT JOIN invited_tutor ON question.question_id = invited_tutor.question_id 
                                WHERE question_status = 1  AND question.status = 1 AND invited_tutor.tutor_id = $tutor_id AND invited_tutor.is_confirmed != 2 $qid $view_condition AND ((TIMESTAMPDIFF(Minute, question.`asked_date`, '".date('Y-m-d H:i:s')."') <= (`time_limit`*60))  OR (question.confirm_select_tutor != 0)) AND invited_tutor.tutor_requst_status IN (0,1) AND invited_tutor.status = 1".$filter_condition ;
         $question_sql = "SELECT question.*,invited_tutor.is_confirmed,invited_tutor.tutor_requst_status,invited_tutor.tutor_bid_amount,
                         TIMESTAMPDIFF(HOUR, question.`asked_date`, '".date('Y-m-d H:i:s')."') as time_diff,
                         (SELECT name FROM qualification where qualification.qualification_id = question.qualification_id) as qualification_name,
                         (SELECT name FROM subject where subject.subject_id = question.subject_id) as subject_name
                         FROM question 
                         LEFT JOIN invited_tutor ON question.question_id = invited_tutor.question_id 
                               WHERE question_status = 1  AND question.status = 1 AND invited_tutor.tutor_id = $tutor_id AND invited_tutor.is_confirmed != 2 $qid $view_condition AND ((TIMESTAMPDIFF(Minute, question.`asked_date`, '".date('Y-m-d H:i:s')."') <= (`time_limit`*60))  OR (question.confirm_select_tutor != 0)) AND invited_tutor.tutor_requst_status IN (0,1) AND invited_tutor.status = 1 $filter_condition
                         $dateorder". $page_condition ;
         }
         else if($question_type == 2){
         $question_array_sql = "SELECT * FROM question 
                                LEFT JOIN invited_tutor ON question.question_id = invited_tutor.question_id 
                                WHERE question_status IN (2,4,5,6,7)  AND question.status = 1 AND question.confirm_select_tutor = $tutor_id $qid $view_condition AND invited_tutor.tutor_id = $tutor_id AND invited_tutor.tutor_requst_status = 1 AND invited_tutor.is_confirmed = 1 AND invited_tutor.status = 1".$filter_condition;
         $question_sql = "SELECT question.*,invited_tutor.is_confirmed,invited_tutor.tutor_requst_status,invited_tutor.tutor_bid_amount,
                          TIMESTAMPDIFF(HOUR, question.`asked_date`, '".date('Y-m-d H:i:s')."') as time_diff,
                         (SELECT name FROM qualification where qualification.qualification_id = question.qualification_id) as qualification_name,
                         (SELECT name FROM subject where subject.subject_id = question.subject_id) as subject_name
                         FROM question 
                         LEFT JOIN invited_tutor ON question.question_id = invited_tutor.question_id 
                                WHERE question_status IN (2,4,5,6,7) AND question.status = 1 AND question.confirm_select_tutor = $tutor_id $qid $view_condition AND invited_tutor.tutor_id = $tutor_id AND invited_tutor.tutor_requst_status = 1 AND invited_tutor.is_confirmed = 1 AND invited_tutor.status = 1 $filter_condition 
                         $dateorder". $page_condition ;
         }
//         echo $question_array_sql;exit;        
         $question_array_data = yii::$app->db->createCommand($question_array_sql)->queryAll(); //print_r($question_array_data);exit;
         $question_array = yii::$app->db->createCommand($question_sql)->queryAll(); 
         
         if(!empty($question_array)){
             foreach ($question_array as $question) {
                 if($question['question_status'] == 2){
                     $mnl = new MasterComponent();
                     $payment = $mnl->payment_detail($question['question_id']);
                 }
                   $answer = []; $student_details = "";$awaiting_student_confirmation=0;
                   if($view == 1 && $question['confirm_select_tutor'] != 0 && ($question['question_status'] == 2||$question['question_status'] == 1)) 
                   {
                       $answer_statement = Chat::find()->where(['chat_id'=>$question['answer_id']])->One();
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
                   if($question['is_confirmed'] == 0 && $question['tutor_requst_status'] == 1){
                       $awaiting_student_confirmation = 1;
                   }
                   else if($question['is_confirmed'] == 2){
                       $awaiting_student_confirmation = 2;
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
                             //'bid_status'=>(int) $question['bid_status'],
                             'tutor_bid_amount'=>(float) $question['tutor_bid_amount'],
                             'asked_date'=>(string) strtotime($question['asked_date']),
                             'current_date'=>(string) time(),
                             'time_diff'=>(int) $question['time_diff'],
                             'completed_date'=> ($question['question_status']==2 ||$question['question_status']==4||$question['question_status']==5||$question['question_status']==6||$question['question_status']==7)? (string) strtotime($question['completed_date']):"",
                             'documents'=> $snl->get_question_docs($question['question_id']),
                             'answer_id'=> (int) $question['answer_id'],
                             'answer'=>  $answer,
                             'student_id'=>(int) $question['created_by'],
                             'role'=>1,
                             'student_details' => $this->get_student_profile($question['created_by'],$view),
                             'payment_type' => (string)$payment['payment_type'],
                             'payment_amount' => (int)$payment['amount'],
                             'tutor_requst_status' => (int) $question['tutor_requst_status'],
                             'awaiting_student_confirmation' => (int) (int) $awaiting_student_confirmation,//0:do not show,1:pending,2:rejected
                             'is_tutor_confirmed'=> (int) $is_tutor_confirmed,
                             'confirmed_tutor_id'=> (int) $question['confirm_select_tutor'],
                             'student_confirmation_status'=> (int) $question['is_confirmed'],
                             'question_status' => $question['question_status'],
                           ];  
                 
             }
         }
         else{
                    $questions = [];
         }
         
                $data['total_records'] = count($question_array_data);
                $data['page_size'] = PAGE_SIZE;
                $data['page_no'] = $page;
                $data['questions'] = $questions;
         if($response == 2){
             return ['count'=>count($question_array_data),'query'=>$question_sql];
         }
         return $data;
    }
    
   /*
     * get tutor profile
     * $view => 0:list,1:detail
     */
    public function get_student_profile($student_id,$view){
        $gnl = new GeneralComponent();
        $snl = new StudentComponent();
        $data = [];
        
        $student = User::findOne($student_id);
        if($student){
        $qualification = Qualification::findOne($student['qualification_id']);
        $rating_data = new User();
        $rating = $rating_data->usergetrating($student_id);
        if($student['role'] == 'student'){$role = 1;}else if($student['role'] == 'tutor'){$role = 2;}
            $data = [
                'student_id' => (int) $student['id'],
                'first_name' => (string) $student['first_name'],
                'last_name' => (string) $student['last_name'],
                'bio' => (string) $student['bio'],
                'email' => (string) $student['email'],
                'profile_photo' => (string) $gnl->image_not_found_api_main('profile_photo', $student['profile_photo']),
                'profile_photo_thumb' => (string) $gnl->image_not_found_api_thumb('profile_photo', $student['profile_photo']),
                'qualification' => (string) $qualification['name'],
                'subjects' => (string) $snl->user_subjects($student['id']),
                'student_rating' => (int)$rating['avg_rating'],
                'student_no_of_user_rate' => (int)$rating['no_of_user'],
                'student_reviews' => ($view == 1) ? $this->get_student_reviews($student_id):[],
                'role' => (int)$role,
            ]; 
        }
        return $data;
    } 
    
    /*
     * get student reviews
     */
    public function get_student_reviews($student_id){
        $gnl = new GeneralComponent();
        $snl = new StudentComponent();
        $data = [];
        
        $review_sql = "SELECT user.* , review.*
                                    FROM review
                                    LEFT JOIN user  ON review.posted_by = user.id 
                                    WHERE  review.posted_for =  $student_id";
        
        $review_array = yii::$app->db->createCommand($review_sql)->queryAll();
            if(!empty($review_array)){
             foreach ($review_array as $review) {
                 if($review['role'] == 'student'){$role = 1;}else if($review['role'] == 'tutor'){$role = 2;}
                   $tutor_who_reviewed[] = [
                             'user_id'=>(int) $review['id'],
                             'first_name'=>(string) $review['first_name'],
                             'last_name'=>(string) $review['last_name'],
                             'bio'=>(string) $review['bio'],
                             'email'=>(string) $review['email'],
                             'profile_photo'=>(string) $gnl->image_not_found_api_main('profile_photo', $review['profile_photo']),
                             'profile_photo_thumb'=>(string) $gnl->image_not_found_api_thumb('profile_photo', $review['profile_photo']),
                             'subjects' => (string)$snl->user_subjects($review['id']),
                             'company_name'=>(string)"",// $review['company_name'],
                             'student_rating'=>(string) $review['rating'],
                             'review_opt'=>(string) $review['review_opt'],
                             'comment'=>(string) $review['comment'],
                            // 'student_no_of_user_rate'=>(integer)0,
                             'role' => (int)$role,
                           ];  
                 
             }
         }
         else{
                    $tutor_who_reviewed = [];
         }
       
        return $tutor_who_reviewed;
    }  
   /*
     * accept question by tutor
     */
    public function accept_question($question_id,$tutor_id,$tutor_bid_amount){
        $gnl = new GeneralComponent();
        $data = [];
        $tutor_confirmed = InvitedTutor::find()->where(['question_id'=>$question_id,'is_confirmed'=>1,'status'=>1])->One();
        if($tutor_confirmed)
        {
            return 2;
        }
        $invited_tutor = InvitedTutor::find()->where(['question_id'=>$question_id,'tutor_id'=>$tutor_id,'is_confirmed'=>0,'status'=>1])->One();
            if(count($invited_tutor) > 0){
                
                $invited_tutor->tutor_requst_status=1;
                $invited_tutor->modified_date=date("Y-m-d H:i:s");
                $question = Question::findOne($question_id);
                if($question->price_type == 2){
                    if($tutor_bid_amount < $question->min_budget && $tutor_bid_amount > $question->max_budget){
                        return 0;
                    }
                $invited_tutor->tutor_bid_amount= $tutor_bid_amount;    
                }
                $invited_tutor->save(FALSE);
                
                $sender = User::findOne($tutor_id);
                $receiver = User::findOne($question['created_by']);
//              -------------------------------------N_PUSHNOTIFICATION1-START---------------------------------------
                        $push_noti_msg = $sender['first_name']." ".$sender['last_name']." has accepted your request to answer question.";
                        $noti_type = 1;
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
                        GeneralComponent::saveNotificationLog($question['question_id'], $sender['id'], $receiver['id'], $noti_type, $push_noti_msg, $sender['id']);
//            -------------------------------------PUSHNOTIFICATION-END---------------------------------------
            //--------------------N_EMAILNOTIFICATION---mail-to-student-start--------------------------------------------
            if($receiver['email_on_tutor_accept'] == 1)
            {
            $email_model = \common\models\EmailTemplate::findOne(6);
            $subject = $email_model->emailtemplate_subject;
                            $bodymessage = $email_model->emailtemplate_body;
                            $bodymessage = str_replace('{tutor}', $sender->first_name.' '.$sender->last_name, $bodymessage);
                            $bodymessage = str_replace('{student}', $receiver->first_name.' '.$receiver->last_name, $bodymessage);
            $gnl->sendMail($receiver->email, $subject, $bodymessage);
            
            }
            //-----------------------------mail-to-student-end--------------------------------------------
                return 1;
            }
            else {
                return 3;
            }
    }  
   /*
     * reject question by tutor
     */
    public function reject_question($question_id,$tutor_id){
        $gnl = new GeneralComponent();
        $data = [];
        $tutor_confirmed = InvitedTutor::find()->where(['question_id'=>$question_id,'tutor_id'=>$tutor_id,'status'=>1])->One();
        if($tutor_confirmed['is_confirmed']==1)
        {
            return 2;
        }
        if($tutor_confirmed['is_confirmed']==2)
        {
            return 4;
        }
        $invited_tutor = InvitedTutor::find()->where(['question_id'=>$question_id,'tutor_id'=>$tutor_id,'is_confirmed'=>0,'status'=>1])->One();
            if(count($invited_tutor) > 0){
                
                $invited_tutor->tutor_requst_status=2;
                $invited_tutor->modified_date=date("Y-m-d H:i:s");
                $question = Question::findOne($question_id);
                $invited_tutor->save(FALSE);
                            
                $sender = User::findOne($tutor_id);
                $receiver = User::findOne($question['created_by']);
//              -------------------------------------N_PUSHNOTIFICATION2-START---------------------------------------
                        $push_noti_msg = $sender['first_name']." ".$sender['last_name']." has rejected your request to answer question.";
                        $noti_type = 2;
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
                        GeneralComponent::saveNotificationLog($question['question_id'], $sender['id'], $receiver['id'], $noti_type, $push_noti_msg, $sender['id']);
//            -------------------------------------PUSHNOTIFICATION-END---------------------------------------
                return 1;
            }
            else {
                return 3;
            }
    } 
    public function add_expertise($post){
        $model = new \common\models\Subject();
        $model->name = ucwords($post['expertise']);
        $model->request_type = 1;
        $model->created_by = $post['user_id'];
        $model->status = 2;
        $model->save(FALSE);
        //-----------------------------mail-to-admin-start--------------------------------------------
        $gnl = new GeneralComponent();
             $adminmail = User::find()->where(['role' => 'superAdmin', 'status' => 1])->one();
             $tutor = User::find()->where(['id'=>$post['user_id']])->one();
            $email_model = \common\models\EmailTemplate::findOne(8);
                            $subject = $email_model->emailtemplate_subject;
                            $bodymessage = $email_model->emailtemplate_body;
                            $bodymessage = str_replace('{tutor}', $tutor['first_name'].' '.$tutor['last_name'], $bodymessage);
                            $messegedata = str_replace('{expertise}', $post['expertise'], $bodymessage);
            $gnl->sendMail($adminmail['email'],$subject, $messegedata);
//-----------------------------mail-to-admin-end--------------------------------------------  
    }
}
