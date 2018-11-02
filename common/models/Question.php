<?php

namespace common\models;

use Yii;
use common\components\GeneralComponent;
use common\models\User;
use common\components\NotificationComponent;

/**
 * This is the model class for table "question".
 *
 * @property integer $question_id
 * @property string $title
 * @property string $description
 * @property integer $time_limit
 * @property integer $is_priority_set
 * @property integer $qualification_id
 * @property integer $subject_id
 * @property integer $price_type
 * @property double $price
 * @property double $min_budget
 * @property double $max_budget
 * @property integer $confirm_bid
 * @property integer $bid_status
 * @property integer $created_by
 * @property string $asked_date
 * @property string $created_date
 * @property string $modified_date
 * @property integer $status
 */
class Question extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    
    public $questiondocument=[];
    public $bid_amount;  
    public $pay_method;
    public $card_number,$card_holder_name,$expiry_month,$expiry_year,$cvv;
    
    
    public static function tableName()
    {
        return 'question';
    }

    /**
     * @inheritdoc
     */
   public function rules()
    {
        return [
                    [['title', 'description', 'time_limit', 'qualification_id', 'subject_id','created_by','price_type','pay_method'], 'required'],
                    [['description','title'], 'string'],
                    [['time_limit', 'is_priority_set', 'qualification_id', 'subject_id', 'price_type', 'confirm_bid', 'bid_status', 'created_by', 'status','answer_id','mark_completed_student','mark_completed_tutor'], 'integer'],
                    [['price', 'min_budget', 'max_budget'], 'number'],
                    
                    ['price', 'required', 'when' => function ($model) {
                return $model->price_type == '1';
            }, 'whenClient' => "function (attribute, value) {
                
                return $('input:radio[name=\'Question[price_type]\']:checked').val() == '1';
            }"],
                   
                    ['min_budget', 'required', 'when' => function ($model) {
                return $model->price_type == '2';
            }, 'whenClient' => "function (attribute, value) {
                return $('input:radio[name=\'Question[price_type]\']:checked').val() == '2';
            }"],
                   
                    ['max_budget', 'required', 'when' => function ($model) {
                return $model->price_type == '2';
            }, 'whenClient' => "function (attribute, value) {
                return $('input:radio[name=\'Question[price_type]\']:checked').val() == '2';
            }"],
                    
                    [['card_number','card_holder_name','expiry_month','expiry_year','cvv'], 'required', 'when' => function ($model) {
                return $model->pay_method == 'direct';
            }, 'whenClient' => "function (attribute, value) {
                return $('input:radio[name=\'Question[pay_method]\']:checked').val() == 'direct';
            }"],
                    
                   
                    [['asked_date', 'created_date', 'modified_date','price','min_budget','max_budget','confirm_bid','bid_status','asked_date','modified_date','bid_amount','admin_commission'], 'safe'],
                    [['title'], 'wordcounttitle'],
                    [['description'], 'wordcountdesc'],
                    [['time_limit'], 'number', 'min' => 1],
                   // ['questiondocument', 'file', 'maxSize' => 20480000, 'tooBig' => 'Limit is 20MB'],
                    [['bid_amount'], 'required', 'on' => ['bidamount']],
                    [['bid_amount'], 'number', 'on' => ['bidamount']],
                    [['bid_amount'], 'limitbudget', 'on' => ['bidamount']],
                    [['card_number','expiry_month','expiry_year','cvv'], 'integer'],
                        
        ];
    }

    public function wordcounttitle($attribute, $params) {
        $count = str_word_count($this->title);
        if ($count > 150) {
            $this->addError($attribute, 'Question Title should not consist more than 150 words');
        }  
    }
    public function wordcountdesc($attribute, $params) {
        $count = str_word_count($this->description);
        if ($count > 500) {
            $this->addError($attribute, 'Description should not consist more than 500 words');
        } 
    }
    public function limitbudget($attribute, $params) {
        $count = str_word_count($this->description);
        if ($this->bid_amount < $this->min_budget || $this->bid_amount > $this->max_budget) {
            $this->addError($attribute, 'Bid Amount is not valid.');
        } 
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        
        if(Yii::$app->controller->action->id == "askquestion")
        {
             return [
            'question_id' => Yii::t('app', 'Question ID'),
            'title' => Yii::t('app', 'Question Title'),
            'description' => Yii::t('app', 'Description'),
            'time_limit' => Yii::t('app', 'Time Limit'),
            'is_priority_set' => Yii::t('app', 'Is Priority Set'),
            'qualification_id' => Yii::t('app', 'Select Qualification'),
            'subject_id' => Yii::t('app', 'Select Subject'),
            'price_type' => Yii::t('app', 'Price Type'),
            'price' => Yii::t('app', 'Price'),
            'min_budget' => Yii::t('app', 'Min Budget'),
            'max_budget' => Yii::t('app', 'Max Budget'),
            'confirm_bid' => Yii::t('app', 'Confirm Bid'),
            'bid_status' => Yii::t('app', 'Bid Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'asked_date' => Yii::t('app', 'Asked Date'),
            'created_date' => Yii::t('app', 'Created Date'),
            'modified_date' => Yii::t('app', 'Modified Date'),
            'cvv' => Yii::t('app', 'CVV'),
            'status' => Yii::t('app', 'Status'),
        ];
             
        }
        else
        {    
        return [
            'question_id' => Yii::t('app', 'Question ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'time_limit' => Yii::t('app', 'Time Limit'),
            'is_priority_set' => Yii::t('app', 'Priority'),
            'qualification_id' => Yii::t('app', 'Qualification'),
            'subject_id' => Yii::t('app', 'Subject'),
            'price_type' => Yii::t('app', 'Price Type'),
            'price' => Yii::t('app', 'Price'),
            'min_budget' => Yii::t('app', 'Minimum Budget'),
            'max_budget' => Yii::t('app', 'Maximum Budget'),
            'confirm_bid' => Yii::t('app', 'Confirm Bid'),
            'bid_status' => Yii::t('app', 'Bid Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'asked_date' => Yii::t('app', 'Asked Date'),
            'created_date' => Yii::t('app', 'Created Date'),
            'modified_date' => Yii::t('app', 'Modified Date'),
            'status' => Yii::t('app', 'Status'),
            'admin_commission' => Yii::t('app', 'Admin Commission (%)'),
        ];
        }
    }
    
    /*
     * ask-question from api and frontend
     * @param $post form data
     * @param  $_FILES data
     * $ptype = post type -> 0 : api,1 : frontend
     */

    public function askquestion($post,$img_doc = [],$ptype) {
        
        $userobj = new User();
        $discount_percent = \common\components\StudentComponent::referral_discoun_check(Yii::$app->user->id);
        $userobj->stripe_customer_create($post['user_id']);
        
        $gnl = new GeneralComponent();
        $model = new Question();
        $model->attributes = $post;
        $model->asked_date = date("Y-m-d H:i:s");
        $model->created_date = date("Y-m-d H:i:s");
        $model->created_by = $post['user_id'];
        
        if ($model->save(false)) {
            
            $question_id = $model->getPrimaryKey();
            
            $userdata = User::findOne($post['user_id']);
            
            /*Payment integration start */
            
            $original_charge_amount = 0;
            if($model->price_type == 1)
            {
                $original_charge_amount = $model->price;
            }
               
            if($model->price_type == 2)
            {
                $original_charge_amount = $model->max_budget;
            }
            
            $charge_amount =  $original_charge_amount;
            
            
            /*apply referral discount */
            
            if($discount_percent)
            {
                $discount_price = $original_charge_amount*($discount_percent/100);
                $charge_amount = $original_charge_amount - $discount_price;
                Yii::$app->db->createCommand()->update('question', ['discount_price' => $discount_price], 'question_id='.$question_id)->execute();
            }
            
            
            
            
           
            $transaction_detail_response = $userobj->stripe_customer_charge($post['pay_method'],
                                                                            $charge_amount, 
                                                                            $userdata['stripe_customer_id'],
                                                                            $question_id,$post['user_id']); 
              
            
            if($transaction_detail_response['status'] == 0)
            {
                Yii::$app->db->createCommand()->delete('question', 'question_id = '.$question_id)->execute();
                return $transaction_detail_response['message'];
            }    
            
            /*Payment integration end */
            $setting = Setting::findOne(1);
            $question = Question::findOne($question_id);
            $question->admin_commission = $setting['manage_commission'];
            $question->save(FALSE);
            
            $model_doc = new QuestionDocument();
            $primary_id = $model->primaryKey;
            $gnl->fileuploadwebservicemultiple(realpath('../../') . '/uploads/', 'question_document', $model_doc, 'document_name','question_id',$primary_id);
            
            if($post['price_type'] == 1){$price = $post['price'];}else{$price = 0;}
            $this->invitetutor($primary_id, $post['tutor_ids'],$post['user_id'],$price);
            
            $user = User::findOne($post['user_id']);
            $user->total_questions = ($user->total_questions)+1;
            $user->save(FALSE);
            
            return 1;
            
        } else {
            return false;
        }
    }
    
    public static function invitetutor($question_id,$tutor_ids,$student_id,$tutor_bid_amount) {
        $gnl = new GeneralComponent();
        $tutor_array = explode(',', $tutor_ids);
        foreach ($tutor_array as $tutor_id) {
            $model = new InvitedTutor();  
            $model->question_id = $question_id;
            $model->tutor_id = $tutor_id;
            $model->tutor_bid_amount = $tutor_bid_amount;
            $model->save(false);
            
                $sender = User::findOne($student_id);
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
    }
}
