<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\components\GeneralComponent;
use frontend\models\UserPaymentInfo;
use common\models\ReferralCode;
use common\models\StudentTutorSubject;
use common\components\StudentComponent;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface {

    const STATUS_DELETED = 2;
    const STATUS_ACTIVE = 1;
    const STATUS_DEACTIVE = 0;

    public $old_password;
    public $rememberMe = true;
    public $confirm_password;
    public $user_type;
    public $bank_name;
    public $account_no;
    public $sort_code;
    public $subject_ids;
    public $expertise_ids;
    public $cv_flag;

    public function getfullName() {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function rules() {


        if (Yii::$app->controller->action->id != "profile") {
            return [

                [['first_name', 'last_name', 'email'], 'required'],
                [[ 'expertise_ids', 'bank_name', 'account_no', 'sort_code'], 'required', 'on' => ['tutorCreate', 'tutorUpdate']],
                [['qualification_id', 'subject_ids'], 'required', 'on' => ['studentCreate', 'studentUpdate']],
                [['password_hash', 'confirm_password'], 'required'],
                [['password_hash', 'confirm_password'], 'required', 'on' => 'changePassword'],
                [['bio'], 'string'],
                [['qualification_id', 'social_type', 'mobile_verified', 'total_questions', 'email_verified', 'payment_verified', 'onboard', 'status', 'created_by', 'modified_by', 'created_at', 'updated_at'], 'integer'],
                [['created_date', 'modified_date', 'user_type', 'bank_name', 'account_no', 'sort_code', 'cv_flag'], 'safe'],
                [['first_name', 'last_name', 'role'], 'string', 'max' => 50],
                [['username', 'password_hash', 'password_reset_token', 'email', 'profile_photo', 'cv_doc', 'company_name', 'social_id', 'university', 'user_last_login'], 'string', 'max' => 255],
                [['auth_key'], 'string', 'max' => 32],
                ['mobile_no', 'number'],
                ['mobile_no', 'string', 'length' => [10, 20]],
//                [['referral_code'], 'string', 'max' => 6],
                [['email'], 'email'],
                [['email'], 'uniqueEmail'],
                [['referral_code'], 'checkRefferalCode'],
                ['cv_doc', 'file', 'extensions' => ['doc', 'docx', 'pdf'], 'maxSize' => 2048000, 'tooBig' => 'Maximun file size limit is 2mb.'],
                [['confirm_password', 'password_hash'], 'string', 'min' => 8],
                [['confirm_password'], 'compare', 'compareAttribute' => 'password_hash', 'skipOnEmpty' => false, 'message' => "Password's do not match."],
                ['profile_photo', 'file', 'extensions' => ['jpg', 'jpeg', 'png', 'bmp']],
            ];
        } else {

            return [

                [['first_name', 'last_name','mobile_no','city','state','zip','paypal_email','bio','schedule_call_fee'], 'required'],
                [['bio'], 'string'],
                [['first_name', 'last_name','city','state','paypal_email'], 'string', 'max' => 50],
                [['facebook_url', 'google_url','twitter_url'], 'string'],
                [['paypal_email'], 'email'],
                [['zip'], 'string', 'max' => 8],
                ['mobile_no', 'number'],
                ['mobile_no', 'string', 'length' => [10, 20]],
                [['schedule_call_fee'], 'number', 'min' => 1],
                //[['blog_fee_one_month','blog_fee_three_month','blog_fee_six_month','blog_fee_one_year','schedule_call_fee'], 'number', 'min' => 1],
                ['profile_photo', 'file', 'extensions' => ['jpg', 'jpeg', 'png', 'bmp']],
               // ['banner_image', 'file', 'extensions' => ['jpg', 'jpeg', 'png', 'bmp']],
            ];
        }
    }

    public function uniqueEmail($attribute, $params) {
        $user = User::find()->where(['id'=>  $this->getPrimaryKey()])->one();
        if($user['email'] != $this->email){
        $usercheck = User::find()->where(['email' => $this->email, 'status' => [0, 1]])->one();
        
        if ($usercheck) {
            $this->addError($attribute, 'Email address already registered!');
        }
        }
    }

    public function checkRefferalCode($attribute, $params) {
        $count = User::find()->where('student_referral_code = "' . $this->referral_code . '" AND status != 2')->count();

        if ($count >= 1) {
            return TRUE;
        } else {
            $this->addError($attribute, 'Invalid referral code.');
        }
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByEmailLogin($email) {

        return static::find()
                        ->where("email = '$email' AND status = 1")
                        ->one();

        //return static::findOne(['email'=>$email,'status'=>self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    public function getPaymentinfo() {
        return $this->hasOne(\frontend\models\UserPaymentInfo::className(), ['user_id' => 'id']);
    }

    public function getQualification() {
        return $this->hasOne(Qualification::className(), ['qualification_id' => 'qualification_id']);
    }

    public function getSubject() {
        return $this->hasOne(Subject::className(), ['subject_id' => 'subject_id']);
    }

    public function getExpertise() {
        return $this->hasOne(Expertise::className(), ['expertise_id' => 'expertise_id']);
    }

    /*
     * User signup from backend
     * @param $post form data
     * @param $cv_doc form $_FILES data
     */

    public function signup($post, $cv_doc = '', $user_type, $model_payment) {
        $gnl = new GeneralComponent();
        $model = new User();
        $model->first_name = $post['first_name'];
        $model->last_name = $post['last_name'];
        $model->email = $post['email'];
        $model->mobile_verified = $post['mobile_verified'];
        $model->email_verified = $post['email_verified'];
        $model->social_type = isset($post['social_type']) ? $post['social_type'] : '';
        $model->social_id = isset($post['social_id']) ? $post['social_id'] : '';
        $model->mobile_no = isset($post['mobile_no']) ? $post['mobile_no'] : '';
        $model->password_hash = isset($post['password_hash']) ? Yii::$app->security->generatePasswordHash($post['password_hash']) : '';
        $referral_code_string = mt_rand(100000, 999999);
        $model->status = 1;
        $model->created_date = date('Y-m-d H:i:s');
        $model->created_at = time();
        if ($user_type == 1) {
            
            $model->student_referral_code = $referral_code_string;
            $model->referral_code = (isset($post['referral_code']) && $post['referral_code'] != "") ? $post['referral_code'] : "";
            $model->qualification_id = $post['qualification_id'];

            $model->role = 'student';
            $model->email_on_tutor_accept = 1;
            $model->email_on_question_complete = 1;
        } else {
            $model->role = 'tutor';
            $model->email_on_selection = 1;
            $model->email_on_invoice = 1;
            $gnl->filedocuploadwebservice(realpath('../../') . '/uploads/', 'cv_doc', $model, 'cv_doc', $cv_doc);
        }
        $model->auth_key = Yii::$app->security->generateRandomString();
        if ($model->save(false)) {
            
              /* zoho create contact */
            $zoho_model = new \common\models\Zohoinvoice();
            $zoho_model->zoho_create_contact($model->id);
            /* end zoho create contact */
            
            
            // Save tutor payment info
            if ($user_type == 2) {
                foreach ($post['expertise_ids'] as $expertise_id) {
                    $model_subject = new StudentTutorSubject();
                    $model_subject->user_id = $model->id;
                    $model_subject->subject_id = $expertise_id;
                    $model_subject->save(FALSE);
                }
            }
            if ($post['user_type'] == 1) {
                
                
             /* stripe create account */
                $userobj = new \common\models\User();
                $userobj->stripe_customer_create($model->id);
                /* end  stripe create account */

                
                
                foreach ($post['subject_ids'] as $subject_id) {
                    $model_subject = new StudentTutorSubject();
                    $model_subject->user_id = $model->id;
                    $model_subject->subject_id = $subject_id;
                    $model_subject->save(FALSE);
                }
            }
            if (isset($post['referral_code']) && $post['referral_code'] != '' && $user_type == 1) {
                $referral_owner = User::findOne(['student_referral_code' => $post['referral_code'], 'status' => [0, 1]]);
                if ($referral_owner) {
                    $user_referral_model = new ReferralCode();
                    $user_referral_model->referral_user_id = $model->id;
                    $user_referral_model->referral_owner_id = $referral_owner['id'];
                    $user_referral_model->referral_code = $referral_owner['student_referral_code'];
                    $user_referral_model->save(false);
                    
                     $total_referral_count = $referral_owner['total_referral_user']+1;
                    Yii::$app->db->createCommand()->update('user', ['total_referral_user' => $total_referral_count], 'id=' . $referral_owner['id'])->execute();
                }
            }

            $encodeUri = $model->auth_key;
            $url = Yii::$app->params['url'] . "site/verification/?auth=" . $encodeUri;
//-----------------------------mail-to-user-start--------------------------------------------
            
            if($user_type == 1)
            {    
                $email_model = \common\models\EmailTemplate::findOne(14);
            }
            else
            {
                $email_model = \common\models\EmailTemplate::findOne(3);
            }   
            
            $subject = str_replace('{appname}', Yii::$app->name, $email_model->emailtemplate_subject);
            $bodymessage = $email_model->emailtemplate_body;
            $bodymessage = str_replace('{username}', $model->first_name, $bodymessage);
            $bodymessage = str_replace('{appname}', Yii::$app->name, $bodymessage);
            $messegedata = str_replace('{url}', $url, $bodymessage);
            $gnl->sendMail($model->email, $subject, $messegedata);
//-----------------------------mail-to-user-end--------------------------------------------
//-----------------------------mail-to-admin-start--------------------------------------------
            $adminmail = \common\models\User::findOne(['role' => 'superAdmin', 'status' => 1]);
            $email_model = \common\models\EmailTemplate::findOne(2);
            $subject_0 = str_replace('{appname}', Yii::$app->name, $email_model->emailtemplate_subject);
            $subject = str_replace('{userRole}', $model->role, $subject_0);
            $bodymessage = $email_model->emailtemplate_body;
            $bodymessage = str_replace('{username}', $model->first_name, $bodymessage);
            $messegedata = str_replace('{appname}', Yii::$app->name, $bodymessage);
            $gnl->sendMail($adminmail['email'], $subject, $messegedata);
//-----------------------------mail-to-admin-end--------------------------------------------            
            return true;
        } else {
            return false;
        }
    }

    public function user_detail_response_webservice($id) {

        $userdetail = User::findOne($id);
        $gnl = new GeneralComponent();
        $snl = new StudentComponent();
        if ($userdetail['role'] == 'user') {
            $role = 1;
        } else if ($userdetail['role'] == 'trainer') {
            $role = 2;
        }
        $qualification_nm = '';
        $q = Qualification::findOne($userdetail['qualification_id']);
        $qualification_nm = $q['name'];
        $rating = \frontend\models\Review::find()->asArray()->select('AVG(rating) as avg_rating')->where('posted_for = ' . $userdetail['id'])->groupBy('posted_for')->one();
        $response = array();
        $response['user_id'] = (integer) $userdetail['id'];
        $response['email'] = (string) $userdetail['email'];
        $response['bio'] = (string) $userdetail['bio'];
        $response['avg_rating'] = number_format($rating['avg_rating'], 1);
        $response['mobile_no'] = (string) $userdetail['mobile_no'];
        $response['role'] = (int) $role;
        $response['first_name'] = (string) $userdetail['first_name'];
        $response['last_name'] = (string) $userdetail['last_name'];
        $response['profile_photo'] = ($userdetail["profile_photo"] != "") ? (string) $gnl->image_not_found_profile_hb_main($userdetail['profile_photo'], 'profile_photo') : "";
        $response['profile_photo_thumb'] = ($userdetail["profile_photo"] != "") ? (string) $gnl->image_not_found_profile_hb($userdetail['profile_photo'], 'profile_photo') : "";
        $response['total_questions'] = (string) $userdetail['total_questions'];
        $response['social_type'] = (int) $userdetail['social_type'];
        $response['social_id'] = (string) $userdetail['social_id'];
        $response['mobile_verified'] = (int) $userdetail['mobile_verified'];
        $response['email_verified'] = (int) $userdetail['email_verified'];
        $response['payment_verified'] = (int) $userdetail['payment_verified'];
        $response['onboard'] = (int) $userdetail['onboard'];
        $response['service_token'] = (string) $userdetail['service_token'];

        if ($userdetail['role'] == 'user') {
            $response['qualification_id'] = (int) $userdetail['qualification_id'];
            $response['qualification_name'] = (string) $qualification_nm;
            $response['subject_ids'] = (string) $snl->user_subjects($id,1);
            $response['subject_name'] = (string) $snl->user_subjects($id);
            $response['referral_code'] = (string) $userdetail['referral_code'];
            $response['email_on_tutor_accept'] = (int) $userdetail['email_on_tutor_accept'];
            $response['email_on_question_complete'] = (int) $userdetail['email_on_question_complete'];
        } elseif ($userdetail['role'] == 'trainer') {
            $response['bio'] = (string) $userdetail['bio'];
            $response['expertise_ids'] = (string) $snl->user_subjects($id);
            $response['university'] = (string) $userdetail['university'];
            $response['cv_doc'] = (string) $gnl->file_not_found_hb($userdetail['cv_doc'], 'cv_doc');
            $response['company_name'] = (string) $userdetail['company_name'];
            $response['email_on_selection'] = (int) $userdetail['email_on_selection'];
            $response['email_on_invoice'] = (int) $userdetail['email_on_invoice'];
        }
        
//        $paymentinfo_data = UserPaymentInfo::findOne(['user_id' => $userdetail['id']]);
//        $payment_info = [];
//        if (!empty($paymentinfo)) {
//            $payment_info['bank_name'] = (string) $paymentinfo_data['bank_name'];
//            $payment_info['account_no'] = (string) $paymentinfo_data['account_no'];
//            $payment_info['sort_code'] = (string) $paymentinfo_data['sort_code'];
//        }
//        $response['payment_details'] = $payment_info;

        return $response;
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'username' => 'Username',
            'bio' => 'Bio',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'mobile_no' => 'Mobile Number',
            'profile_photo' => 'Profile Photo',
            'role' => 'Role',
            'qualification_id' => 'Qualification',
            'subject_ids' => 'Subject',
            'cv_doc' => 'Cv Doc',
            'company_name' => 'Company Name',
            'bank_name' => 'Bank Name',
            'account_no' => 'Account Number',
            'sort_code' => 'Sort Code',
            'expertise_ids' => 'Expertise',
            'referral_code' => 'Referral Code',
            'social_type' => 'Social Type',
            'social_id' => 'Social ID',
            'mobile_verified' => 'Mobile Verified',
            'university' => 'University',
            'paypal_email' => 'Paypal Email',
            'email_verified' => 'Email Verified',
            'user_last_login' => 'User Last Login',
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'blog_fee_one_month' => 'One Month fee ($)',
            'blog_fee_three_month' => 'Three Months fee ($)',
            'blog_fee_six_month' => 'Six Months fee ($)',
            'blog_fee_one_year' => 'One Year fee ($)',
            'banner_image' => 'Banner Image',
            'schedule_call_fee' => 'Schedule Call Fee ($)',
        ];
    }

    public function usergetrating($userid) {
        $result = Yii::$app->db->createCommand("SELECT ROUND(AVG(rating)) as avg_rating,count(*) as no_of_user FROM review WHERE posted_for = $userid")->queryOne();
        if (!$result) {
            $result = ['avg_rating' => 0, 'no_of_user' => 0];
        }
        return $result;
    }

    public function stripe_customer_create($user_id) {

        $userdata = User::findOne($user_id);
        if ($userdata['stripe_customer_id'] == "") {
            
            $settingdata = Setting::findOne(1);
            \Stripe\Stripe::setApiKey($settingdata->stripe_payment);   
            
            $customer_stripe_data = \Stripe\Customer::create(
                            array(
                                "email" => $userdata['email'],
                            )
            );

            Yii::$app->db->createCommand()->update('user', ['stripe_customer_id' => $customer_stripe_data->id], 'id=' . $userdata['id'])->execute();
        }
        return true;
    }

    public function stripe_add_cart_to_customer($user_id, $card_array) {
        $settingdata = Setting::findOne(1);
        
        \Stripe\Stripe::setApiKey($settingdata->stripe_payment);
        $userdata = User::findOne($user_id);
        $response = array();
        $response['stripe_data'] = array();
        $response['status'] = 1;
        $response['message'] = "Success";

        try {
            
            $stripe_card_array = $card_array;
            unset($stripe_card_array['card_holder_name']);
            
            $customer = \Stripe\Customer::retrieve($userdata['stripe_customer_id']);
            $carddetail = $customer->sources->create(array("source" => $stripe_card_array));

            $student_card_detail = array();
            $student_card_detail['user_id'] = $user_id;
            $student_card_detail['stripe_card_id'] = $carddetail->id;
            $student_card_detail['brand'] = $carddetail->brand;
            $student_card_detail['country'] = $carddetail->country;
            $student_card_detail['stripe_customer_id'] = $carddetail->customer;
            $student_card_detail['card_holder_name'] = $card_array['card_holder_name'];
            $student_card_detail['cvc_check'] = $carddetail->cvc_check;
            $student_card_detail['exp_month'] = $carddetail->exp_month;
            $student_card_detail['exp_year'] = $carddetail->exp_year;
            $student_card_detail['fingerprint'] = $carddetail->fingerprint;
            $student_card_detail['funding'] = $carddetail->funding;
            $student_card_detail['last_four'] = $carddetail->last4;
            $student_card_detail['created_by'] = $user_id;
            $student_card_detail['created_date'] = date('Y-m-d H:i:s');

            Yii::$app->db->createCommand()->insert('student_card_detail', $student_card_detail)->execute();
            $response['stripe_data'] = $student_card_detail;
        } catch (\Stripe\Error\Card $e) {
            $stripe_error = $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $stripe_error;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $stripe_error = $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $stripe_error;
        } catch (\Stripe\Error\Authentication $e) {
            $stripe_error = $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $stripe_error;
        } catch (\Stripe\Error\ApiConnection $e) {
            $stripe_error = $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $stripe_error;
        } catch (\Stripe\Error $e) {
            $stripe_error = $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $stripe_error;
        } catch (Exception $e) {
            $stripe_error = $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $stripe_error;
        }

        return $response;
    }

    public function stripe_customer_charge($stripe_card_id, $amount, $stripe_customer_id,$question_id,$user_id) {
        $response = array();
        $response['stripe_data'] = array();
        $response['status'] = 1;
        $response['message'] = "Success";

        
       
        try {
            $amount = $amount;
            $amount = $amount * 100;
            
            if($amount > 0)
            {    
                $settingdata = Setting::findOne(1);
                \Stripe\Stripe::setApiKey($settingdata->stripe_payment);   

                $charge_detail = \Stripe\Charge::create(array(
                            "amount" => $amount,
                            "currency" => "GBP",
                            "customer" => $stripe_customer_id,
                            "card" => $stripe_card_id,
                ));
            }
               
            
            $student_transaction_detail = array();
            
            $student_transaction_detail['user_id'] = $user_id;
            $student_transaction_detail['question_id'] = $question_id;
            $student_transaction_detail['stripe_charge_id'] = @$charge_detail->id;
            $student_transaction_detail['stripe_card_id'] = @$charge_detail->source->id;
            $student_transaction_detail['amount'] = @$charge_detail->amount/100;
            $student_transaction_detail['amount_refunded'] = @$charge_detail->amount_refunded/100;
            $student_transaction_detail['balance_transaction'] = @$charge_detail->balance_transaction;
            $student_transaction_detail['captured'] = @$charge_detail->captured;
            $student_transaction_detail['stripe_customer_id'] = @$charge_detail->customer;
            $student_transaction_detail['failure_code'] = @$charge_detail->failure_code;
            $student_transaction_detail['failure_message'] = @$charge_detail->failure_message;
            $student_transaction_detail['paid'] = @$charge_detail->paid;
            $student_transaction_detail['created_by'] = $user_id;
            $student_transaction_detail['created_date'] = date('Y-m-d H:i:s');
            
            Yii::$app->db->createCommand()->insert('transaction', $student_transaction_detail)->execute();
            
            $transaction_id = Yii::$app->db->getLastInsertID();
            $studypad_txn_id = Yii::$app->params['TXN_PREFIX'].sprintf('%04d',$transaction_id);
            Yii::$app->db->createCommand()->update('transaction', ['studypad_txn_id' => $studypad_txn_id], "transaction_id=$transaction_id")->execute();
            
            $response['stripe_data'] = $student_transaction_detail;
            
            
        } catch (\Stripe\Error\Card $e) {
            $stripe_error = $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $stripe_error;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $stripe_error = $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $stripe_error;
        } catch (\Stripe\Error\Authentication $e) {
            $stripe_error = $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $stripe_error;
        } catch (\Stripe\Error\ApiConnection $e) {
            $stripe_error = $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $stripe_error;
        } catch (\Stripe\Error $e) {
            $stripe_error = $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $stripe_error;
        } catch (Exception $e) {
            $stripe_error = $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $stripe_error;
        }

        return $response;
    }

}
