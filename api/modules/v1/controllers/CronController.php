<?php

namespace api\modules\v1\controllers;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\Controller;
use common\models\User;
use common\models\Question;
use common\models\Chat;
use common\components\GeneralComponent;
use common\components\NotificationComponent;
use common\models\Setting;
use common\models\NotificationGeneralization;

class CronController extends Controller {

  public function actionChatSessionTimeout() {
      $setting = Setting::findOne(1);
      $chat_session_timeout = $setting['chat_session_timeout'];
     $question_sql = "SELECT question.question_id, 
                      TIMESTAMPDIFF(HOUR, invited_tutor.`student_accept_time`, '".date('Y-m-d H:i:s')."') as diff,
                      IF((SELECT chat_id FROM chat WHERE chat.question_id = question.question_id  LIMIT 1), 1, 0) as chat_started
                      FROM question 
                      LEFT JOIN `invited_tutor` 
                      ON question.question_id = invited_tutor.question_id 
                      WHERE question_status = 1  AND confirm_select_tutor != 0 
                      AND question.question_id NOT IN (SELECT chat.question_id FROM chat WHERE chat.question_id = question.question_id)
                      
                      AND invited_tutor.tutor_id = question.confirm_select_tutor
                      AND TIMESTAMPDIFF(Minute, invited_tutor.`student_accept_time`, '".date('Y-m-d H:i:s')."') > ($chat_session_timeout*60)" ;
     $question_array = yii::$app->db->createCommand($question_sql)->queryAll(); 
//     print_r($chat_session_timeout);print_r('<br>');print_r($question_array);exit;
     $i = 0;
     foreach ($question_array as $value) {
                    $listarray[$i] = $value['question_id'];
                    $i++;
                }
     $adata = array();
     $adata['question_status'] = 4;//CANCELLED
     $adata['completed_date'] = date("Y-m-d H:i:s");//CANCELLED
     yii::$app->db->createCommand()->update('question', $adata, ['question_id'=>$listarray] )->execute();  
    }
    
    /*
     * notification before 24 hours of chat-timeout
     */
    public function actionChatSessionTimeoutNotify() {
      $setting = Setting::findOne(1);
      $chat_session_timeout = ($setting['chat_session_timeout']-24);
     $question_sql = "SELECT question.question_id, question.created_by, question.confirm_select_tutor, 
                      TIMESTAMPDIFF(HOUR, invited_tutor.`student_accept_time`, '".date('Y-m-d H:i:s')."') as diff,
                      IF((SELECT chat_id FROM chat WHERE chat.question_id = question.question_id  LIMIT 1), 1, 0) as chat_started
                      FROM question 
                      LEFT JOIN `invited_tutor` 
                      ON question.question_id = invited_tutor.question_id 
                      WHERE question_status = 1   AND confirm_select_tutor != 0 
                      AND question.question_id NOT IN (SELECT chat.question_id FROM chat WHERE chat.question_id = question.question_id)
                      
                      AND invited_tutor.tutor_id = question.confirm_select_tutor
                      AND TIMESTAMPDIFF(Minute, invited_tutor.`student_accept_time`, '".date('Y-m-d H:i:s')."') > ($chat_session_timeout*60)" ;
     $question_array = yii::$app->db->createCommand($question_sql)->queryAll(); 
//     print_r($chat_session_timeout);print_r('<br>');print_r($question_array);exit;
     $sender = User::findOne(1);
     $i = 0;
     foreach ($question_array as $question) {
                $receiver = User::findOne($question['created_by']);
                $notification = NotificationGeneralization::find()->where(['notification_type'=>4,'studypad_id'=>$question['question_id'],'notification_to'=>$receiver['id']])->one();
                if(empty($notification) && !empty($receiver)){
//              -------------------------------------N_PUSHNOTIFICATION4-START---------------------------------------
                        $push_noti_msg = "Your chat session will be expired in 24 hours and question will be cancelled.";
                        $noti_type = 4;
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
                }
                        $receiver = User::findOne($question['confirm_select_tutor']);
                        $notification = NotificationGeneralization::find()->where(['notification_type'=>14,'studypad_id'=>$question['question_id'],'notification_to'=>$receiver['id']])->one();
                if(empty($notification) && !empty($receiver)){
//              -------------------------------------N_PUSHNOTIFICATION14-START---------------------------------------
                        $push_noti_msg = "Your chat session will be expired in 24 hours and question will be cancelled.";
                        $noti_type = 14;
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
                }
                }
       
    }
    
  public function actionStudentAcceptAnswerTimeout() {
      $setting = Setting::findOne(1);
      $student_accept_answer_timeout = $setting['student_accept_answer_timeout'];
      
      $questions = Question::find()
              ->select('question.*,(SELECT chat_id FROM chat WHERE chat.question_id = question.question_id AND sender_id = question.confirm_select_tutor AND marked_answer_tutor = 1 AND status = 1) as answer_id')
              ->where(['mark_completed_tutor'=>1,'mark_completed_student'=>0])
              ->andWhere("TIMESTAMPDIFF(Minute, `tutor_marked_time`, '".date('Y-m-d H:i:s')."') > ($student_accept_answer_timeout*60)")
              ->asArray()->All();
      
//      print_r($questions);exit;
        foreach ($questions as $question) {
            $question_update = Question::find()->where(['question_id'=>$question['question_id']])->one();
                        $question_update->mark_completed_student = 1;
                        $question_update->question_status = 2;
                        $question_update->answer_id = $question['answer_id'];
                        $question_update->completed_date = date("Y-m-d H:i:s");  
                        $question_update->student_marked_time = date("Y-m-d H:i:s");
                        $question_update->save(FALSE);
             $sender = User::findOne(1);
                $receiver = User::findOne($question['created_by']);
                if(!empty($receiver)){
//              -------------------------------------N_PUSHNOTIFICATION6-START---------------------------------------
                        $push_noti_msg = "Your question is completed.";
                        $noti_type = 6;
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
            if($receiver['email_on_question_complete'] == 1)
            {
            $email_model = \common\models\EmailTemplate::findOne(9);
            $subject = $email_model->emailtemplate_subject;
                            $bodymessage = $email_model->emailtemplate_body;
                            $bodymessage = str_replace('{student}', $receiver->first_name.' '.$receiver->last_name, $bodymessage);
            $gnl->sendMail($receiver->email, $subject, $bodymessage);
            
                }
            //-----------------------------mail-to-student-end--------------------------------------------
        }
    }
    }
    
  public function actionQuestionTimeout() {
     $question_sql = "SELECT * FROM question 
                      WHERE question_status = 1  AND status = 1   AND confirm_select_tutor = 0
                      AND TIMESTAMPDIFF(Minute, question.`asked_date`, '".date('Y-m-d H:i:s')."') > (`time_limit`*60) " ;
     $question_array = yii::$app->db->createCommand($question_sql)->queryAll(); 
     $i = 0;
     foreach ($question_array as $value) {
                    $listarray[$i] = $value['question_id'];
                    $i++;
                }
     $adata = array();
     $adata['question_status'] = 3;//Expired
     yii::$app->db->createCommand()->update('question', $adata, ['question_id'=>$listarray] )->execute();  
    } 
    
    
    
    public function actionGetinvoicefromzoho()
    {
        //$query = "SELECT transaction.transaction_id,transaction.studypad_txn_id,transaction.question_id FROM transaction LEFT JOIN invoice ON invoice.zoho_reference_number = transaction.studypad_txn_id WHERE transaction.studypad_txn_id != '' AND invoice.invoice_id IS NULL";
        //$transaction_data = Yii::$app->db->createCommand($query)->queryAll();
       
      
                    
                    $zohomodel = new \common\models\Zohoinvoice();
                    $invocedetail = $zohomodel->zoho_get_invoice('');
                    
                    
                    
                    if($invocedetail)
                    {
                        foreach ($invocedetail as $value)
                        {
                            
                            $query = "SELECT invoice_id FROM invoice WHERE zoho_invoice_id = '".$value->invoice_id."'";
                            $invoice_check = Yii::$app->db->createCommand($query)->queryAll();
                            if($invoice_check)
                            {
                                continue;
                            }    
       
                            
                            $invoice_single_detail = $zohomodel->getInvoicePdf($value->invoice_id);
                            if($invoice_single_detail)
                            {    

                                    $zoho_invoice_detail = array();
                                    $zoho_invoice_detail['zoho_invoice_id'] = $invoice_single_detail->invoice_id;
                                    $zoho_invoice_detail['zoho_invoice_number'] = $invoice_single_detail->invoice_number;
                                    $zoho_invoice_detail['zoho_salesorder_id'] = $invoice_single_detail->salesorder_id;
                                    $zoho_invoice_detail['zoho_ach_payment_initiated'] = $invoice_single_detail->ach_payment_initiated;
                                    $zoho_invoice_detail['zoho_zcrm_potential_id'] = $invoice_single_detail->zcrm_potential_id;
                                    $zoho_invoice_detail['zoho_zcrm_potential_name'] = $invoice_single_detail->zcrm_potential_name;
                                    $zoho_invoice_detail['zoho_date'] = $invoice_single_detail->date;
                                    $zoho_invoice_detail['zoho_status'] = $invoice_single_detail->status;
                                    $zoho_invoice_detail['zoho_payment_terms'] = $invoice_single_detail->payment_terms;
                                    $zoho_invoice_detail['zoho_payment_terms_label'] = $invoice_single_detail->payment_terms_label;
                                    $zoho_invoice_detail['zoho_due_date'] = $invoice_single_detail->due_date;
                                    $zoho_invoice_detail['zoho_payment_expected_date'] = $invoice_single_detail->payment_expected_date;
                                    $zoho_invoice_detail['zoho_payment_discount'] = $invoice_single_detail->payment_discount;
                                    $zoho_invoice_detail['zoho_stop_reminder_until_payment_expected_date'] = $invoice_single_detail->stop_reminder_until_payment_expected_date;
                                    $zoho_invoice_detail['zoho_last_payment_date'] = $invoice_single_detail->last_payment_date;
                                    $zoho_invoice_detail['zoho_reference_number'] = $invoice_single_detail->reference_number;
                                    $zoho_invoice_detail['zoho_customer_id'] = $invoice_single_detail->customer_id;
                                    $zoho_invoice_detail['zoho_estimate_id'] = $invoice_single_detail->estimate_id;
                                    $zoho_invoice_detail['zoho_is_client_review_settings_enabled'] = $invoice_single_detail->is_client_review_settings_enabled;
                                    $zoho_invoice_detail['zoho_customer_name'] = $invoice_single_detail->customer_name;
                                    $zoho_invoice_detail['zoho_unused_retainer_payments'] = $invoice_single_detail->unused_retainer_payments;
                                    $zoho_invoice_detail['zoho_contact_persons'] = serialize($invoice_single_detail->contact_persons);
                                    $zoho_invoice_detail['zoho_currency_id'] = $invoice_single_detail->currency_id;
                                    $zoho_invoice_detail['zoho_currency_code'] = $invoice_single_detail->currency_code;
                                    $zoho_invoice_detail['zoho_currency_symbol'] = $invoice_single_detail->currency_symbol;
                                    $zoho_invoice_detail['zoho_exchange_rate'] = $invoice_single_detail->exchange_rate;
                                    $zoho_invoice_detail['zoho_discount'] = $invoice_single_detail->discount;

                                    $zoho_invoice_detail['zoho_discount_applied_on_amount'] = $invoice_single_detail->discount_applied_on_amount;
                                    $zoho_invoice_detail['zoho_is_discount_before_tax'] = $invoice_single_detail->is_discount_before_tax;

                                    $zoho_invoice_detail['zoho_discount_type'] = $invoice_single_detail->discount_type;
                                    $zoho_invoice_detail['zoho_recurring_invoice_id'] = $invoice_single_detail->recurring_invoice_id;
                                    $zoho_invoice_detail['zoho_documents'] = serialize($invoice_single_detail->documents);

                                    $zoho_invoice_detail['zoho_is_viewed_by_client'] = $invoice_single_detail->is_viewed_by_client;
                                    $zoho_invoice_detail['zoho_client_viewed_time'] = $invoice_single_detail->client_viewed_time;
                                    $zoho_invoice_detail['zoho_is_inclusive_tax'] = $invoice_single_detail->is_inclusive_tax;

                                    $zoho_invoice_detail['zoho_schedule_time'] = $invoice_single_detail->schedule_time;
                                    $zoho_invoice_detail['zoho_line_items'] = serialize($invoice_single_detail->line_items);

                                    $zoho_invoice_detail['zoho_contact_persons_details'] = serialize($invoice_single_detail->contact_persons_details);
                                    $zoho_invoice_detail['zoho_shipping_charge'] = $invoice_single_detail->shipping_charge;

                                    $zoho_invoice_detail['zoho_adjustment'] = $invoice_single_detail->adjustment;
                                    $zoho_invoice_detail['zoho_adjustment_description'] = $invoice_single_detail->adjustment_description;
                                    $zoho_invoice_detail['zoho_late_fee'] = serialize($invoice_single_detail->late_fee);


                                    $zoho_invoice_detail['zoho_sub_total'] = $invoice_single_detail->sub_total;
                                    $zoho_invoice_detail['zoho_tax_total'] = $invoice_single_detail->tax_total;
                                    $zoho_invoice_detail['zoho_total'] = $invoice_single_detail->total;
                                    $zoho_invoice_detail['zoho_taxes'] = serialize($invoice_single_detail->taxes);

                                    $zoho_invoice_detail['zoho_payment_reminder_enabled'] = $invoice_single_detail->payment_reminder_enabled;
                                    $zoho_invoice_detail['zoho_payment_made'] = $invoice_single_detail->payment_made;
                                    $zoho_invoice_detail['zoho_credits_applied'] = $invoice_single_detail->credits_applied;
                                    $zoho_invoice_detail['zoho_tax_amount_withheld'] = $invoice_single_detail->tax_amount_withheld;
                                    $zoho_invoice_detail['zoho_balance'] = $invoice_single_detail->balance;
                                    $zoho_invoice_detail['zoho_write_off_amount'] = $invoice_single_detail->write_off_amount;
                                    $zoho_invoice_detail['zoho_allow_partial_payments'] = $invoice_single_detail->allow_partial_payments;
                                    $zoho_invoice_detail['zoho_price_precision'] = $invoice_single_detail->price_precision;
                                    $zoho_invoice_detail['zoho_payment_options'] = serialize($invoice_single_detail->payment_options); 
                                    $zoho_invoice_detail['zoho_is_emailed'] = $invoice_single_detail->is_emailed; 
                                    $zoho_invoice_detail['zoho_reminders_sent'] = $invoice_single_detail->reminders_sent; 
                                    $zoho_invoice_detail['zoho_last_reminder_sent_date'] = $invoice_single_detail->last_reminder_sent_date; 
                                    $zoho_invoice_detail['zoho_next_reminder_date_formatted'] = $invoice_single_detail->next_reminder_date_formatted; 
                                    $zoho_invoice_detail['zoho_billing_address'] = serialize($invoice_single_detail->billing_address); 
                                    $zoho_invoice_detail['zoho_shipping_address'] = serialize($invoice_single_detail->shipping_address); 
                                    $zoho_invoice_detail['zoho_notes'] = $invoice_single_detail->notes; 
                                    $zoho_invoice_detail['zoho_terms'] = $invoice_single_detail->terms; 
                                    $zoho_invoice_detail['zoho_custom_fields'] = serialize($invoice_single_detail->custom_fields); 
                                    $zoho_invoice_detail['zoho_custom_field_hash'] = serialize($invoice_single_detail->custom_field_hash); 
                                    $zoho_invoice_detail['zoho_template_id'] = $invoice_single_detail->template_id; 
                                    $zoho_invoice_detail['zoho_template_name'] = $invoice_single_detail->template_name; 
                                    $zoho_invoice_detail['zoho_template_type'] = $invoice_single_detail->template_type; 
                                    $zoho_invoice_detail['zoho_page_width'] = $invoice_single_detail->page_width; 
                                    $zoho_invoice_detail['zoho_page_height'] = $invoice_single_detail->page_height; 
                                    $zoho_invoice_detail['zoho_orientation'] = $invoice_single_detail->orientation; 
                                    $zoho_invoice_detail['zoho_created_time'] = $invoice_single_detail->created_time; 
                                    $zoho_invoice_detail['zoho_last_modified_time'] = $invoice_single_detail->last_modified_time;   
                                    $zoho_invoice_detail['zoho_created_by_id'] = $invoice_single_detail->created_by_id;   
                                    $zoho_invoice_detail['zoho_attachment_name'] = $invoice_single_detail->attachment_name;   
                                    $zoho_invoice_detail['zoho_can_send_in_mail'] = $invoice_single_detail->can_send_in_mail;   
                                    $zoho_invoice_detail['zoho_salesperson_id'] = $invoice_single_detail->salesperson_id;   
                                    $zoho_invoice_detail['zoho_salesperson_name'] = $invoice_single_detail->salesperson_name;   
                                    $zoho_invoice_detail['zoho_is_autobill_enabled'] = $invoice_single_detail->is_autobill_enabled;   
                                    $zoho_invoice_detail['zoho_invoice_url'] = $invoice_single_detail->invoice_url;   
                                    
                                    
                                    $zoho_invoice_detail['created_by'] = 1;
                                    $zoho_invoice_detail['created_date'] = date('Y-m-d H:i:s');
                                    
                                    $user_detail = User::findOne(['zoho_contact_id'=>$invoice_single_detail->customer_id]);
                                    if($user_detail)
                                    {
                                        $zoho_invoice_detail['user_id'] = $user_detail['id'];
                                      
                                    }    
                                    
                                    /*
                                    $zoho_invoice_detail['transaction_id'] = $tvalue['transaction_id'];   
                                    $questiondetail = Question::findOne($tvalue['question_id']);
                                    $zoho_invoice_detail['question_id'] = $tvalue['question_id'];   
                                    $zoho_invoice_detail['tutor_id'] = $questiondetail['confirm_select_tutor'];   
                                    $zoho_invoice_detail['student_id'] = $questiondetail['created_by'];   
                                    */
                                    
                                    Yii::$app->db->createCommand()->insert('invoice', $zoho_invoice_detail)->execute();
                                    $invoice_id = Yii::$app->db->getLastInsertID();
                                    if($user_detail){
    if($user_detail['role'] == 'tutor'){
    $receiver = $user_detail;
 //              -------------------------------------N_PUSHNOTIFICATION16-START---------------------------------------
                        $push_noti_msg = "New transaction is done.";
                        $noti_type = 16;
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
                        GeneralComponent::saveNotificationLog($invoice_id, 1, $receiver['id'], $noti_type, $push_noti_msg, 1);
//            -------------------------------------PUSHNOTIFICATION-END---------------------------------------
                
                //--------------------N_EMAILNOTIFICATION---mail-to-tutor-start--------------------------------------------
             $gnl = new GeneralComponent();
            if($receiver['email_on_selection'] == 1){          
            $email_model = \common\models\EmailTemplate::findOne(13);
            $subject = $email_model->emailtemplate_subject;
                            $bodymessage = $email_model->emailtemplate_body;
                            $bodymessage = str_replace('{tutor}', $receiver->first_name.' '.$receiver->last_name, $bodymessage);
            $gnl->sendMail($receiver->email, $subject, $bodymessage);
            }
            
            //-----------------------------mail-to-tutor-end--------------------------------------------   
}                                          
                                    }
                                    if($invoice_single_detail->line_items)
                                    {
                                        foreach ($invoice_single_detail->line_items as $ivalue)
                                        {
                                            $invoice_item_detail = array();
                                            $invoice_item_detail['invoice_id'] = $invoice_id;
                                            $invoice_item_detail['zoho_invoice_id'] = $invoice_single_detail->invoice_id;
                                            $invoice_item_detail['line_item_id'] = $ivalue->line_item_id;
                                            $invoice_item_detail['item_id'] = $ivalue->item_id;
                                            $invoice_item_detail['salesorder_item_id'] = $ivalue->salesorder_item_id;
                                            $invoice_item_detail['project_id'] = $ivalue->project_id;
                                            $invoice_item_detail['time_entry_ids'] = $ivalue->time_entry_ids;
                                            $invoice_item_detail['expense_id'] = $ivalue->expense_id;
                                            $invoice_item_detail['expense_receipt_name'] = $ivalue->expense_receipt_name;
                                            $invoice_item_detail['name'] = $ivalue->name;
                                            $invoice_item_detail['description'] = $ivalue->description;
                                            $invoice_item_detail['item_order'] = $ivalue->item_order;
                                            $invoice_item_detail['bcy_rate'] = $ivalue->bcy_rate;
                                            $invoice_item_detail['rate'] = $ivalue->rate;
                                            $invoice_item_detail['quantity'] = $ivalue->quantity;
                                            $invoice_item_detail['unit'] = $ivalue->unit;
                                            $invoice_item_detail['discount_amount'] = $ivalue->discount_amount;
                                            $invoice_item_detail['discount'] = $ivalue->discount;
                                            $invoice_item_detail['tax_id'] = $ivalue->tax_id;
                                            $invoice_item_detail['tax_name'] = $ivalue->tax_name;
                                            $invoice_item_detail['tax_type'] = $ivalue->tax_type;
                                            $invoice_item_detail['tax_percentage'] = $ivalue->tax_percentage;
                                            $invoice_item_detail['item_total'] = $ivalue->item_total;
                                            $invoice_item_detail['documents'] = serialize($ivalue->documents);
                                            $invoice_item_detail['item_custom_fields'] = serialize($ivalue->item_custom_fields);
                                        
                                            Yii::$app->db->createCommand()->insert('invoice_item', $invoice_item_detail)->execute();
                                            
                                        }
                                        
                                    }    
                                   
                            }    
                        }
                    }    
         
        echo "Invoice get successfully";
        die;
                
       
    }  
   
    /*
     * notification before 24 hours of question-timeout
     */
    public function actionQuestionTimeoutNotify() {
     $question_sql = "SELECT * FROM question 
                      WHERE question_status = 1  AND status = 1   AND confirm_select_tutor = 0
                      AND TIMESTAMPDIFF(Minute, question.`asked_date`, '".date('Y-m-d H:i:s')."') > ((`time_limit`-1)*60) " ;
     $question_array = yii::$app->db->createCommand($question_sql)->queryAll(); 
//     print_r($question_array);exit;
     $sender = User::findOne(1);
     $i = 0;
     foreach ($question_array as $question) { 
                $receiver = User::findOne($question['created_by']);
                $notification = NotificationGeneralization::find()->where(['notification_type'=>3,'studypad_id'=>$question['question_id'],'notification_to'=>$receiver['id']])->one();
                 
                if(empty($notification) && !empty($receiver)){
//              -------------------------------------N_PUSHNOTIFICATION3-START---------------------------------------
                        $push_noti_msg = "Your question will be expired in 1 hour.";
                        $noti_type = 3;
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
              $gnl = new GeneralComponent();        
            $email_model = \common\models\EmailTemplate::findOne(10);
            $subject = $email_model->emailtemplate_subject;
                            $bodymessage = $email_model->emailtemplate_body;
                            $bodymessage = str_replace('{student}', $receiver->first_name.' '.$receiver->last_name, $bodymessage);
            $gnl->sendMail($receiver->email, $subject, $bodymessage);
            
            //-----------------------------mail-to-student-end--------------------------------------------  
                }
                }
       
    }   
}
