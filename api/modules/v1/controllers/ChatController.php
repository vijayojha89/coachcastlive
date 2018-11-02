<?php

namespace api\modules\v1\controllers;

use yii;
use yii\helpers\ArrayHelper;
use common\components\GeneralComponent;
use yii\web\Controller;
use common\models\User;
use common\models\Chat;
use common\components\NotificationComponent;
use common\components\ChatComponent;

class ChatController extends Controller {

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /*
     * parameters : question_id,user_id(sender_id),receiver_id,message_type,message/file
     * message_type : 0 => text, 1 => file
     */

    public function actionAddMessage() {
        $gnl = new GeneralComponent();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
       
        $result = $data = [];
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
        //check for parameters
        if (isset($post['question_id']) && $post['question_id'] > 0 && isset($post['user_id']) && $post['user_id'] > 0 && isset($post['receiver_id']) && isset($post['message_type']) && $post['message_type'] != '') {
        
        $question_id = $post['question_id'];
        $user_id = $post['user_id'];
        $receiver_id = $post['receiver_id'];
        $message_type = $post['message_type'];
        $message = $post['message'];
        $question = \common\models\Question::find()->where(['confirm_select_tutor'=>$user_id,'created_by'=>$receiver_id,'question_id'=>$question_id,'question_status'=>1,'mark_completed_student'=>0,'mark_completed_tutor'=>0])
                                                   ->orWhere(['confirm_select_tutor'=>$receiver_id,'created_by'=>$user_id,'question_id'=>$question_id,'question_status'=>1,'mark_completed_student'=>0,'mark_completed_tutor'=>0])->One();
        
        if(empty($question)){
                        $result['status'] = 0;
                        $result['message'] = 'Question not available';
                        header('Content-type: application/json');
                        echo json_encode($result);
                        exit();
        }
            if ($message_type == 0) {
                if ($message == '') {
                    $result['status'] = 0;
                    $result['message'] = Yii::t('app', 'message cannot be blank');
                    header('Content-type: application/json');
                    echo json_encode($result);
                    exit();
                }
            }
            if ($message_type == 1) {
                if (!isset($_FILES['file_name']['name']) && $_FILES['file_name']['name'] == '') {
                    $result['status'] = 0;
                    $result['message'] = Yii::t('app', 'File cannot be blank');
                    header('Content-type: application/json');
                    echo json_encode($result);
                    exit();
                }
            }
            $user = User::find()->where(['id'=>$user_id,'status'=>1])->asArray()->one();
            //check login status
            if (!empty($user)) {
                    //insert chat
                    $model = new Chat();
                    $model->question_id = $question_id;
                    $model->sender_id = $user_id;
                    $model->receiver_id = $receiver_id;
                    $model->message_type = $message_type;//0 => text, 1 => file
                    $model->message = ($message_type == 0)?$message:"";
                    if($message_type == 1){
                        $model->file_original_name = $_FILES['file_name']['name'];
                        $gnl->filedocuploadwebservice(realpath('../../') . '/uploads/', 'chat_files', $model, 'file_name');
                    }
                    $model->created_by = $user_id;
                    $model->created_date = date("Y-m-d H:i:s");
                    $model->save(false);
                    $sql = "SELECT chat_id,receiver_seen,(SELECT CONCAT(first_name,' ',last_name) FROM user where user.id = chat.sender_id) as owner_name,
                        sender_id,(SELECT CONCAT(first_name,' ',last_name) FROM user where user.id = chat.receiver_id) as receiver_name,
                        (SELECT profile_photo FROM user where user.id = chat.sender_id) as owner_profile_photo,
                        (SELECT profile_photo FROM user where user.id = chat.receiver_id) as receiver_profile_photo,
                        receiver_id, message, message_type,file_name,file_original_name, created_date
                        FROM chat 
                        WHERE chat_id = '" . $model->chat_id . "'";
                    $chat = yii::$app->db->createCommand($sql)->queryOne();
                    //response
                    $data = [
                        "chat_id" => (int) $chat["chat_id"],
                        "sender_id" => (int) $chat["sender_id"],
                        "owner_name" => (string) $chat["owner_name"],
                        "receiver_id" => (int) $chat["receiver_id"],
                        "receiver_name" => (string) $chat["receiver_name"],
                        "receiver_profile_photo" =>  (string) $gnl->image_not_found_api_main('profile_photo', $chat["receiver_profile_photo"]) ,
                        "owner_profile_photo" => (string) $gnl->image_not_found_api_main('profile_photo', $chat["owner_profile_photo"]) ,
                        "receiver_profile_photo_thumb" => (string) $gnl->image_not_found_api_thumb('profile_photo', $chat["receiver_profile_photo"]) ,
                        "owner_profile_photo_thumb" => (string) $gnl->image_not_found_api_thumb('profile_photo', $chat["owner_profile_photo"]) ,
                        "message" => (string) $chat["message"],
                        "message_type" => (int) $chat["message_type"],
                        "file_name" => (string)  $gnl->image_not_found_api_main('chat_files', $chat['file_name']) ,
                        "file_name_thumb" => (string)  $gnl->image_not_found_api_thumb('chat_files', $chat['file_name']) ,
                        "file_name_original" =>  (string) $chat['file_original_name'] ,
                        "receiver_seen" => (int) $chat["receiver_seen"],
                        "created_date" => (double) strtotime($chat["created_date"]),
                    ];
                    //if success
                    if ($model->chat_id) {
                       
                        $result['status'] = 1;
                        $result['message'] = Yii::t('app', 'Chat added successfully');
                        $result['data'] = $data;
                    } else {
                        $result['status'] = 0;
                        $result['message'] = Yii::t('app', 'Chat not added');
                    }
            } else {
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'User not found');
        }
        } else {
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'Invalid parameters');
        }
        return $result;
    }

   
  

  



    public function actionTest() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $result = $data = [];
        
        $current_datetime = date("Y-m-d H:i:s");
        
        $result['current_datetime'] = $current_datetime;
        return $result;
        exit;
    }

    
    /**
     * Get chat list
     * Params - sender_id, receiver_id,service_token,question_id,page
     * message_order => 1 : prev , 2 : next
     */
    public function actionGetChatList() {
        $gnl = new GeneralComponent();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
       
        $result = $data = [];
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
        //check for parameters
        if (isset($post['question_id']) && $post['question_id'] > 0 && isset($post['user_id']) && $post['user_id'] > 0 &&
            isset($post['chat_id'])   ) {
        
        $question_id = $post['question_id'];
        $user_id = $post['user_id'];
        $page = 1;
        $question = \common\models\Question::find()->where(['question_id'=>$question_id,'confirm_select_tutor'=>$user_id])
                                                   ->orWhere(['question_id'=>$question_id,'created_by'=>$user_id])->One();
        if(empty($question)){
                        $result['status'] = 0;
                        $result['message'] = 'Question not available';
                        header('Content-type: application/json');
                        echo json_encode($result);
                        exit();
        }
            $user = User::find()->where(['id'=>$user_id,'status'=>1])->asArray()->one();
            if (!empty($user)) {
                    //check for last msg id
                if(isset($post['chat_id']) && $post['chat_id'] > 0){
                    if($post['message_order'] != 1 && $post['message_order'] != 2){
                        $result['status'] = 0;
                        $result['message'] = 'Send message order.';
                        header('Content-type: application/json');
                        echo json_encode($result);
                        exit();
                    }
                     $message_order = '';
                    if($post['message_order'] == 1) {
                        $message_order = 'AND chat_id < '.$post['chat_id'].'';
                    }
                    if($post['message_order'] == 2) {
                        $message_order = 'AND chat_id > '.$post['chat_id'].'';
                    }
                }
                    $seen_sql = "UPDATE `chat` SET `receiver_seen` = 1
                             WHERE sender_id = '" . $receiver_id . "' AND owner_del = 0 AND receiver_del = 0 ";

                    $mark_as_read = yii::$app->db->createCommand($seen_sql)->execute();

                    $sql = "SELECT chat.*, (SELECT CONCAT(first_name,' ',last_name) FROM user where user.id = chat.sender_id) as owner_name,
                        sender_id,(SELECT CONCAT(first_name,' ',last_name) FROM user where user.id = chat.receiver_id) as receiver_name,
                        (SELECT profile_photo FROM user where user.id = chat.receiver_id) as receiver_profile_photo,
                        (SELECT profile_photo FROM user where user.id = chat.sender_id) as owner_profile_photo,
                        receiver_id, message, message_type,file_name,file_original_name, created_date
                        FROM chat 
                        WHERE question_id = $question_id AND status = 1 AND (sender_id = '" . $user_id . "'   AND owner_del = 0
                               OR receiver_id = '" . $user_id . "' AND receiver_del = 0 ) $message_order
                               ORDER BY chat_id ASC";
                $chat_data = yii::$app->db->createCommand($sql)->queryAll();
                
                foreach ($chat_data as $chat) {
                    // print_r($chat);
                $data[] =     [
                        "chat_id" => (int) $chat["chat_id"],
                        "sender_id" => (int) $chat["sender_id"],
                        "owner_name" => (string) $chat["owner_name"],
                        "receiver_id" => (int) $chat["receiver_id"],
                        "receiver_name" => (string) $chat["receiver_name"],
                        "receiver_profile_photo" =>  (string) $gnl->image_not_found_api_main('profile_photo', $chat["receiver_profile_photo"]) ,
                        "owner_profile_photo" => (string) $gnl->image_not_found_api_main('profile_photo', $chat["owner_profile_photo"]) ,
                        "receiver_profile_photo_thumb" => (string) $gnl->image_not_found_api_thumb('profile_photo', $chat["receiver_profile_photo"]) ,
                        "owner_profile_photo_thumb" => (string) $gnl->image_not_found_api_thumb('profile_photo', $chat["owner_profile_photo"]) ,
                        "message" => (string) $chat["message"],
                        "message_type" => (int) $chat["message_type"],
                        "file_name" => (string)  $gnl->image_not_found_api_main('chat_files', $chat['file_name']) ,
                        "file_name_thumb" => (string)  $gnl->image_not_found_api_thumb('chat_files', $chat['file_name']) ,
                        "file_name_original" =>  (string) $chat['file_original_name'] ,
                        "marked_answer_tutor" => (int) $chat["marked_answer_tutor"],
                        "accepted_answer_student" => (int) $chat["accepted_answer_student"],
                        "receiver_seen" => (int) $chat["receiver_seen"],
                        "created_date" => (double) strtotime($chat["created_date"]),
                    ];
                } //print_r($data);exit;
                
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'Chat listed successfully');
//                $result['total_records'] = count($data);
                $result['data'] = $data;
//                $result['page'] = (int)$page;
                if (count($data) < CHAT_PAGE_SIZE || $page <= 0) {
//                    $result['data'] = $data;
                } else {
                    if ((CHAT_PAGE_SIZE * $page) > count($data)) {
                        $data = array_slice($data, (count($data) * (-1)), (CHAT_PAGE_SIZE - ((CHAT_PAGE_SIZE * $page) - count($data))));
                    } else {
                        $data = array_slice($data, (CHAT_PAGE_SIZE * $page * (-1)), CHAT_PAGE_SIZE);
                    }
                }
                 $chat_for_limit = yii::$app->db->createCommand('
                     SELECT MIN(chat_id) as min_chat_id,MAX(chat_id) as max_chat_id FROM chat WHERE 
                     question_id ='. $question_id.' AND status = 1 AND (sender_id = ' . $user_id . '   AND owner_del = 0
                               OR receiver_id = '. $user_id . ' AND receiver_del = 0 )
                         ')->queryOne(); 
                 $chat_list = [
                    'min_chat_id'=>$chat_for_limit['min_chat_id'],
                    'max_chat_id'=>$chat_for_limit['max_chat_id'],
                     'chat' => $data
                ];
                  $result['data'] = $chat_list;
//                 array_push($v,$data);
            } else {
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'User not found');
            }
        } else {
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'Invalid parameters');
        }
        return $result;
    }

    /**
     * Delete chat
     * Params - chat_id(comma separated), user_id,service_token
     * 
     */
    public function actionDelChat() {
        $gnl = new GeneralComponent();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
       
        $result = $data = [];
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
        //check for parameters
        if (isset($post['user_id']) && $post['user_id'] > 0  && isset($post['chat_id']) && $post['chat_id'] > 0 ) {
        
        $user_id = $post['user_id'];
        $chat_id = $post['chat_id'];
        $chat = Chat::find()->where(['chat_id'=>$chat_id,'sender_id'=>$user_id])
                                                   ->orWhere(['chat_id'=>$chat_id,'receiver_id'=>$user_id])->One();
        if(empty($chat)){
                        $result['status'] = 0;
                        $result['message'] = 'Chat not available';
                        header('Content-type: application/json');
                        echo json_encode($result);
                        exit();
        }
            $user = User::find()->where(['id'=>$user_id,'status'=>1])->asArray()->one();
            if (!empty($user)) {
                $chat_id_array = explode(",", $chat_id);
                if (!empty($chat_id_array)) {
                    foreach ($chat_id_array as $chat_id) {
                        $model = Chat::find()->where(['chat_id' => $chat_id])->One();
                        if (!empty($model)) {
                            //check user is owner OR receiver
                            if ($model->sender_id == $user_id) {
                                $model->owner_del = 2;
                            } else {
                                $model->receiver_del = 2;
                            }
                            //save model
                            if ($model->save(false)) {
                                $result['status'] = 1;
                                $result['message'] = Yii::t('app', 'success');
                            } else {
                                $result['status'] = 0;
                                $result['message'] = Yii::t('app', 'There is an error occurred while deleting chat.  Please try again later.');
                            }
                        } else {
                            $result['status'] = 1;
                            $result['message'] = Yii::t('app', 'No record found');
                        }
                    }
                } else {
                    $result['status'] = 0;
                    $result['message'] = Yii::t('app', 'No chats found');
                }
            } else {
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'User not found');
            }
        }  else {
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'Invalid parameters');
        }
        return $result;
    }
    
     /**
     * Delete chat list
     * Params - question_id, user_id,service_token
     * 
     */
    public function actionDelChatList() {
        $gnl = new GeneralComponent();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
       
        $result = $data = [];
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
        //check for parameters
        if (isset($post['question_id']) && $post['question_id'] > 0 && isset($post['user_id']) && $post['user_id'] > 0 ) {
        
        $question_id = $post['question_id'];
        $user_id = $post['user_id'];
        
        $question = \common\models\Question::find()->where(['question_id'=>$question_id,'confirm_select_tutor'=>$user_id])
                                                   ->orWhere(['question_id'=>$question_id,'created_by'=>$user_id])->One();
        if(empty($question)){
                        $result['status'] = 0;
                        $result['message'] = 'Question not available';
                        header('Content-type: application/json');
                        echo json_encode($result);
                        exit();
        }
            $user = User::find()->where(['id'=>$user_id,'status'=>1])->asArray()->one();
            if (!empty($user)) {
                $chats = ArrayHelper::getColumn(Chat::find()
                                        ->where(["question_id" => $question_id])
                                        ->asArray()
                                        ->all(), 'chat_id');

                if (!empty($chats)) {
                    //update all the records
                    $update_owner = Chat::updateAll(["owner_del" => 2], ["chat_id" => $chats, 'sender_id' => $user_id]);
                    $update_receiver = Chat::updateAll(["receiver_del" => 2], ["chat_id" => $chats, 'receiver_id' => $user_id]);

                        $result['status'] = 1;
                        $result['message'] = Yii::t('app', 'success');
                    
                } else {
                    $result['status'] = 1;
                    $result['message'] = Yii::t('app', 'No record found');
                }
            } else {
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'User not found');
            }
        }  else {
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'Invalid parameters');
        }
        return $result;
    }
    
     /**
     * student/tutor completes the question
     * Params for student - service_token,question_id,user_id,user_type
     * Params for tutor - service_token,question_id,user_id,chat_id,user_type
     * user_type - student : 1 ,tutor : 2
     * 
     */
    public function actionMarkCompleted() {
        $gnl = new GeneralComponent();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
       
        $result = $data = [];
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
        //check for parameters
        if (isset($post['question_id']) && $post['question_id'] > 0 && isset($post['user_id']) && $post['user_id'] > 0 ) {
        $cnl = new ChatComponent;
        $data = $cnl->mark_completed($post);
        
                if($data == 2){
                $result['status'] = 0;
                $result['message'] = 'Chat id is required.';
                }
          
                if($data == 3){
                $result['status'] = 0;
                $result['message'] = 'Already marked.';
                }
        
                if($data == 4){
                        $result['status'] = 0;
                        $result['message'] = 'Question not available.';
                }
                if($data == 5){
                        $result['status'] = 0;
                        $result['message'] = 'There is no answer for this question yet.';
                }
                if($data == 1){
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'Answer is marked as completed.');
                }
                if($data == 6){
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'Answer is selected.');
                }
                if($data == 0){
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'User not found.');
                }
                if($data == 7){
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'Tutor has already selected answer.please confirm answer to complete question.');
                }
            
        }  else {
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'Invalid parameters');
        }
        return $result;
    }    
     /**
     * student confirms answer after tutor selected answer
     * Params- service_token,question_id,user_id,chat_id,accept
     * accept - 1:yes ,2 : no
     */
    public function actionStudentAnswerConfirm() {
        $gnl = new GeneralComponent();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
       
        $result = $data = [];
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
        //check for parameters
        if (isset($post['question_id']) && $post['question_id'] > 0 && isset($post['user_id']) && $post['user_id'] > 0  
                && isset($post['chat_id']) && $post['chat_id'] > 0 && isset($post['accept']) && $post['accept'] > 0 ) {
        
                $cnl = new ChatComponent;
                $data = $cnl->student_answer_confirm($post);
                if($data == 0){
                                $result['status'] = 0;
                                $result['message'] = 'Question not available';
                }
                if($data == 2 ){

                        $result['status'] = 0;
                        $result['message'] = 'Already marked.';
                }
                if($data == 1 ){
                        $result['status'] = 1;
                        $result['message'] = Yii::t('app', 'Success');
                    } 
                if($data == 3 ){
                        $result['status'] = 0;
                        $result['message'] = Yii::t('app', 'User not found');
                    }
            
        }  else {
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'Invalid parameters');
        }
        return $result;
    }
  public function actionUpdateAnswerTutor() {
        $gnl = new GeneralComponent();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
  
        $result = $data = [];
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
        //check for parameters
        if (isset($post['question_id']) && $post['question_id'] > 0 && isset($post['user_id']) && $post['user_id'] > 0 ) {
        $cnl = new ChatComponent;
        $data = $cnl->update_answer_tutor($post);
        
                if($data == 2){
                $result['status'] = 0;
                $result['message'] = 'Chat id is required.';
                }
          
                if($data == 3){
                $result['status'] = 0;
                $result['message'] = 'Already marked as completed.';
                }
        
                if($data == 4){
                        $result['status'] = 0;
                        $result['message'] = 'Question not available.';
                }
                if($data == 5){
                        $result['status'] = 0;
                        $result['message'] = 'There is no answer for this question yet.';
                }
                if($data == 1){
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'Answer is marked as completed.');
                }
           
                if($data == 0){
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'User not found.');
                }
            
        }  else {
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'Invalid parameters');
        }
        return $result;
    }

}