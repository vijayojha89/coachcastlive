<?php

namespace common\components;

use yii;
use yii\helpers\ArrayHelper;
use common\components\GeneralComponent;
use yii\web\Controller;
use common\models\User;
use common\models\Chat;
use common\components\NotificationComponent;
use yii\base\Component;

class ChatComponent extends Component {
    
    public function mark_completed($post) {
       $gnl = new GeneralComponent();
        $question_id = $post['question_id'];
        $user_id = $post['user_id'];
        $user_type = $post['user_type'];
        if($user_type == 2 ){
            if(isset($post['chat_id']) && $post['chat_id']> 0){
               $chat_id = $post['chat_id']; 
            }
            else{
                return 2;//Chat id is required
           }
           $check_chat = Chat::find()->where(['question_id'=>$question_id,'status'=>1,'marked_answer_tutor'=>1])->one();
           if(!empty($check_chat)){
               return 3;//Already marked
           }
        }
        if($user_type == 1 ){
           $check_chat = Chat::find()->where(['question_id'=>$question_id,'status'=>1,'accepted_answer_student'=>1])->one();
           if(!empty($check_chat)){
               return 3;//Already marked
           }
        }
        
        $question = \common\models\Question::find()->where(['question_id'=>$question_id,'confirm_select_tutor'=>$user_id])
                                                   ->orWhere(['question_id'=>$question_id,'created_by'=>$user_id])
                ->andWhere("((TIMESTAMPDIFF(Minute, question.`asked_date`, '".date('Y-m-d H:i:s')."') <= (`time_limit`*60))  OR (question.confirm_select_tutor != 0))")
                ->One();
        if(empty($question)){
            return 4;//Question not available
        }
        $chat_intitiated = Chat::find()->where(['question_id'=>$question_id,'sender_id'=>$question['confirm_select_tutor']])->asArray()->all();
        if(count($chat_intitiated) < 1){
            return 5;//There is no answer for this question yet.
        }
        
            $user = User::find()->where(['id'=>$user_id,'status'=>1])->asArray()->one();
            if (!empty($user)) {
                /* if student marks complete question 'mark_completed_student' = 1 
                 * if tutor marks complete question 'mark_completed_tutor' = 1 and if student has already marked then answer_id will be set
                 * 
                 */
                $sender = $user;
                if($user_type == 1){
                    if($question->mark_completed_tutor == 1){
                        return 7;//tutor has already selected answer.please confirm answer to complete question.
                    }
                    $question->mark_completed_student = 1;
                    $question->question_status = 2;
                    $question->completed_date = date("Y-m-d H:i:s");
                    $question->student_marked_time = date("Y-m-d H:i:s");
                    $question->save(FALSE);
                    
                    
                    $receiver = User::findOne($question['confirm_select_tutor']);
//              -------------------------------------N_PUSHNOTIFICATION15-START---------------------------------------
                        $push_noti_msg = $sender['first_name']." ".$sender['last_name']." has completed the question.Please select answer.";
                        $noti_type = 15;
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
                       $receiver_student = User::findOne($question['created_by']);
//--------------------N_EMAILNOTIFICATION---mail-to-student-start--------------------------------------------
                        if($receiver_student['email_on_question_complete'] == 1)
                        {
                        $email_model = \common\models\EmailTemplate::findOne(9);
                        $subject = $email_model->emailtemplate_subject;
                                        $bodymessage = $email_model->emailtemplate_body;
                                        $bodymessage = str_replace('{student}', $receiver_student->first_name.' '.$receiver_student->last_name, $bodymessage);
                        $gnl->sendMail($receiver_student->email, $subject, $bodymessage);

                        }
            //-----------------------------mail-to-student-end--------------------------------------------
             //--------------------N_EMAILNOTIFICATION---mail-to-tutor-start--------------------------------------------
                       
                         $email_model = \common\models\EmailTemplate::findOne(11);
                         $subject = $email_model->emailtemplate_subject;
                                         $bodymessage = $email_model->emailtemplate_body;
                                         $bodymessage = str_replace('{tutor}', $receiver->first_name.' '.$receiver->last_name, $bodymessage);
                                         $bodymessage = str_replace('{student}', $receiver_student->first_name.' '.$receiver_student->last_name, $bodymessage);
                         $gnl->sendMail($receiver->email, $subject, $bodymessage);
            
            //-----------------------------mail-to-tutor-end--------------------------------------------
                    
                    
                    
                    
                    return 1;//Answer is marked
                }
                if($user_type == 2){
                    $question->answer_id = $chat_id;
                    $question->mark_completed_tutor = 1;//student needs to confirm answer to complete the question
                    /*if($question->mark_completed_student == 1 && $question->question_status == 1){
                        $select_answer_tutor = 1;
                        $question->question_status = 2;
                        $question->completed_date = date("Y-m-d H:i:s");
                    }   
                     * 
                     */
                    $question->tutor_marked_time = date("Y-m-d H:i:s");
                    $question->save(FALSE);

                    $chat = Chat::find()->where(['question_id'=>$question_id,'chat_id'=>$chat_id])->One();
                    $chat->marked_answer_tutor = 1;
                    $chat->receiver_del = 0;
                    $chat->save(FALSE);
                    if($question->mark_completed_student == 1 && $question->question_status == 2){

                        return 6;//answer is selected by tutor
                    }
                    else{
                        
                    $receiver = User::findOne($question['created_by']);
//              -------------------------------------N_PUSHNOTIFICATION5-START---------------------------------------
                        $push_noti_msg = $sender['first_name']." ".$sender['last_name']." has selected the answer.Please confirm answer to complete the question.";
                        $noti_type = 5;
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
                        $receiver_tutor = User::findOne($question['confirm_select_tutor']);
                        $email_model = \common\models\EmailTemplate::findOne(12);
                        $subject = $email_model->emailtemplate_subject;
                                        $bodymessage = $email_model->emailtemplate_body;
                                        $bodymessage = str_replace('{student}', $receiver->first_name.' '.$receiver->last_name, $bodymessage);
                                        $bodymessage = str_replace('{tutor}', $receiver_tutor->first_name.' '.$receiver_tutor->last_name, $bodymessage);
                        $gnl->sendMail($receiver->email, $subject, $bodymessage);
            
 //-----------------------------mail-to-student-end--------------------------------------------
                        
                    return 1;//Answer is marked as completed by tutor first
                    }
                }
              
                
            } else {
                return 0;//User not found
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'User not found');
            }
          
    }    
     
    public function student_answer_confirm($post) {
        
        
        $question_id = $post['question_id'];
        $user_id = $post['user_id'];
        $chat_id = $post['chat_id'];
        
        $question = \common\models\Question::find()->where(['question_id'=>$question_id,'created_by'=>$user_id,'mark_completed_student'=>0])
                ->andWhere("((TIMESTAMPDIFF(Minute, question.`asked_date`, '".date('Y-m-d H:i:s')."') <= (`time_limit`*60))  OR (question.confirm_select_tutor != 0))")
                ->One();
        if(empty($question)){
            return 0 ; //Question not available
        }
        if($question['mark_completed_student'] == 1 ){
           $check_chat = Chat::find()->where(['question_id'=>$question_id,'status'=>1,'accepted_answer_student'=>1])->one();
           
           if(!empty($check_chat)){
               return 2; //Already marked.
           }
        }
            $user = User::find()->where(['id'=>$user_id,'status'=>1])->asArray()->one();
            if (!empty($user)) {

                    $chat = Chat::find()->where(['question_id'=>$question_id,'marked_answer_tutor'=>1,'status'=>1,'sender_id'=>$question['confirm_select_tutor']])->One();
                    $chat->accepted_answer_student = $post['accept'];
                    $chat->save(FALSE);
                    if($post['accept'] == 1){
                        $question_update = \common\models\Question::find()->where(['question_id'=>$question_id])->one();
                        $question_update->mark_completed_student = 1;
                        $question_update->question_status = 2;
                        $question_update->answer_id = $chat_id;
                        $question_update->completed_date = date("Y-m-d H:i:s");  
                        $question_update->student_marked_time = date("Y-m-d H:i:s");
                        $question_update->save(FALSE);
                    }
                    else if($post['accept'] == 2){
                        $question_update = \common\models\Question::find()->where(['question_id'=>$question_id])->one();
                        $question_update->mark_completed_student = 1;
                        $question_update->question_status = 5;//CANCELLED
                        $question_update->answer_id = $chat_id;
                        $question_update->completed_date = date("Y-m-d H:i:s");  
                        $question_update->student_marked_time = date("Y-m-d H:i:s");
                        $question_update->save(FALSE);
                    }
                 return 1; //Success.
            } else {
                 return 3; //User not found.
            }
       
    }
    
    
    public function update_answer_tutor( $post) {
        
        
        $question_id = $post['question_id'];
        $user_id = $post['user_id'];
        $chat_id = $post['chat_id'];
        
        $question = \common\models\Question::find()->where(['question_id'=>$question_id,'confirm_select_tutor'=>$user_id])
                ->andWhere("((TIMESTAMPDIFF(Minute, question.`asked_date`, '".date('Y-m-d H:i:s')."') <= (`time_limit`*60))  OR (question.confirm_select_tutor != 0))")
                ->One();
        if(empty($question)){
            return 0 ; //Question not available
}
        if($question['mark_completed_tutor'] == 1 ){
           $check_chat = Chat::find()->where(['question_id'=>$question_id,'status'=>1,'accepted_answer_student'=>1])->one();
           
           if(!empty($check_chat)){
               return 2; //Already marked.
           }
        }
            $user = User::find()->where(['id'=>$user_id,'status'=>1])->asArray()->one();
            if (!empty($user)) {

                    $chat = Chat::find()->where(['question_id'=>$question_id,'chat_id'=>$chat_id,'marked_answer_tutor'=>0,'status'=>1,'sender_id'=>$user_id])->One();
                    if($chat){
                    $chat->marked_answer_tutor = 1;
                    $chat->receiver_del = 0;
                    $chat->save(FALSE);
                    
                    
                    $question->answer_id = $chat_id;
                    $question->tutor_marked_time = date("Y-m-d H:i:s");
                    $question->save(FALSE);
                    
                 return 1; //Success.
            }
            else{
                return 5;//No chat found.
            }
            } else {
                 return 3; //User not found.
            }
       
    }
}
