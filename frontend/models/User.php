<?php

namespace frontend\models;

use Yii;
use common\components\GeneralComponent;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $bio
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $mobile_no
 * @property string $profile_photo
 * @property string $role
 * @property integer $qualification_id
 * @property string $subject_ids
 * @property string $cv_doc
 * @property string $company_name
 * @property string $expertise_ids
 * @property string $referral_code
 * @property integer $social_type
 * @property string $social_id
 * @property integer $mobile_verified
 * @property string $university
 * @property integer $total_questions
 * @property integer $email_verified
 * @property string $user_last_login
 * @property integer $status
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends \yii\db\ActiveRecord {

    public $confirm_password;
    public $user_type;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['first_name', 'last_name', 'email'], 'required'],
            [['password_hash', 'confirm_password', 'mobile_no'], 'required', 'on' => 'trainer_signup'],
            [['password_hash', 'confirm_password'], 'required', 'on' => 'student_signup'],
            [['bio'], 'string'],
            [['social_type', 'mobile_verified', 'email_verified', 'status', 'created_by', 'modified_by', 'created_at', 'updated_at'], 'integer'],
            [['created_date', 'modified_date', 'user_type'], 'safe'],
            [['first_name', 'last_name', 'role'], 'string', 'max' => 50],
            [['username', 'password_hash', 'password_reset_token', 'email', 'profile_photo', 'social_id', 'user_last_login'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            ['mobile_no', 'number'],
            ['mobile_no', 'string', 'length' => [10, 20]],
            [['email'], 'email'],
            [['email'], 'uniqueEmail'],
            [['confirm_password', 'password_hash'], 'string', 'min' => 8],
            [['confirm_password'], 'compare', 'compareAttribute' => 'password_hash', 'skipOnEmpty' => false, 'message' => "Password's do not match."],
            [['profile_photo'], 'file', 'extensions' => 'jpg, jpeg, png, bmp'],
        ];
    }

    //Check unique email validation
    public function uniqueEmail($attribute, $params) {
        /* $where = 'email = "' . $this->email . '" AND status = 1';
          if (!Yii::$app->user->isGuest) {
          $where .= ' AND id != ' . $this->id;
          }
          $user = User::find()->where($where)->one();
         * 
         */
        $user = User::find()->where(['email' => $this->email, 'status' => [0, 1]])->one();
        if (!empty($user))
            $this->addError($attribute, 'Email address already registered.');
    }

    /**
     * @inheritdoc
     */
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
            'total_questions' => 'Total Questions',
            'email_verified' => 'Email Verified',
            'user_last_login' => 'User Last Login',
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /*
     * User signup
     * @param $post form data
     * @param $cv_doc form $_FILES data
     */

    public function signup($post) {
        $gnl = new GeneralComponent();
        $model = new User();
        $model->first_name = $post['first_name'];
        $model->last_name = $post['last_name'];
        $model->email = $post['email'];
        $model->social_type = isset($post['social_login_type']) ? $post['social_login_type'] : '';
        $model->social_id = isset($post['social_login_id']) ? $post['social_login_id'] : '';
        $model->mobile_no = isset($post['mobile_no']) ? $post['mobile_no'] : '';
        if (isset($post['password'])) {
            $model->password_hash = isset($post['password']) ? Yii::$app->security->generatePasswordHash($post['password']) : Yii::$app->security->generatePasswordHash($post['password']);
        }
        if (isset($post['password_hash'])) {
            $model->password_hash = isset($post['password_hash']) ? Yii::$app->security->generatePasswordHash($post['password_hash']) : Yii::$app->security->generatePasswordHash($post['password_hash']);
        }

        $referral_code_string = uniqid();
        if ($post['user_type'] == 1) {
            $model->role = 'user';
        } else {
            $model->role = 'trainer';
        }
        $model->auth_key = Yii::$app->security->generateRandomString();
        if ($model->save(false)) {

            $encodeUri = $model->auth_key;
            $url = Yii::$app->params['url'] . "site/verification/?auth=" . $encodeUri;

//-----------------------------mail-to-user-start--------------------------------------------
            $email_model = \common\models\EmailTemplate::findOne(3);
            $subject = str_replace('{appname}', Yii::$app->name, $email_model->emailtemplate_subject);
            $bodymessage = $email_model->emailtemplate_body;
            $bodymessage = str_replace('{username}', $model->first_name, $bodymessage);
            $bodymessage = str_replace('{appname}', Yii::$app->name, $bodymessage);
            $messegedata = str_replace('{url}', $url, $bodymessage);
            $gnl->sendMail($model->email, $subject, $messegedata);
//-----------------------------mail-to-user-end--------------------------------------------
//-----------------------------mail-to-admin-start--------------------------------------------
            if ($post['user_type'] == 1) {
                $adminmail = \common\models\User::findOne(['role' => 'superAdmin', 'status' => 1]);
                $email_model = \common\models\EmailTemplate::findOne(2);
                $subject_0 = str_replace('{appname}', Yii::$app->name, $email_model->emailtemplate_subject);
                $subject = str_replace('{userRole}', $model->role, $subject_0);
                $bodymessage = $email_model->emailtemplate_body;
                $bodymessage = str_replace('{username}', $model->first_name, $bodymessage);
                $messegedata = str_replace('{appname}', Yii::$app->name, $bodymessage);
                $gnl->sendMail($adminmail['email'], $subject, $messegedata);
            }
//-----------------------------mail-to-admin-end--------------------------------------------            
            return true;
        } else {
            return false;
        }
    }

    /*
     * User profileupdate
     * @param $post form data
     * @param $cv_doc form $_FILES data
     */

    public function updateprofile($post) {

        $gnl = new GeneralComponent();
        if ($model = User::find()->where(["id" => $post['user_id'], 'status' => 1])->one()) {

            $model->modified_by = $post['user_id'];
            $model->modified_date = date("Y-m-d H:i:s");
            $model->first_name = (isset($post['first_name']) && $post['first_name'] != '') ? $post['first_name'] : $model->first_name;
            $model->last_name = (isset($post['last_name']) && $post['last_name'] != '') ? $post['last_name'] : $model->last_name;
            $model->bio = (isset($post['bio']) && $post['bio'] != '') ? $post['bio'] : $model->bio;
            $model->mobile_no = (isset($post['mobile_no']) && $post['mobile_no'] != '') ? $post['mobile_no'] : $model->mobile_no;
            $gnl->fileuploadwebservice(realpath('../../') . '/uploads/', 'profile_photo', $model, 'profile_photo');

            if ($model->role == 'student') {
                $model->qualification_id = (isset($post['qualification_id']) && $post['qualification_id'] != '') ? $post['qualification_id'] : $model->qualification_id;
            }
            if ($model->role == 'tutor') {
                $gnl->filedocuploadwebservice(realpath('../../') . '/uploads/', 'cv_doc', $model, 'cv_doc', $cv_doc);
            }
            $model->save(false);
            if ($model->role == 'tutor' && $post['expertise_ids'] != '') {
                StudentTutorSubject::deleteAll(['user_id' => $model->id]);
                foreach ($post['expertise_ids'] as $expertise_id) {
                    $model_subject = new StudentTutorSubject();
                    $model_subject->user_id = $model->id;
                    $model_subject->subject_id = $expertise_id;
                    $model_subject->save(FALSE);
                }
            }
            if ($model->role == 'student' && $post['subject_ids'] != '') {
                StudentTutorSubject::deleteAll(['user_id' => $model->id]);
                foreach ($post['subject_ids'] as $subject_id) {
                    $model_subject = new StudentTutorSubject();
                    $model_subject->user_id = $model->id;
                    $model_subject->subject_id = $subject_id;
                    $model_subject->save(FALSE);
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->status = 1;
            $this->created_date = date('Y-m-d H:i:s');
            $this->created_at = time();
        } else {
            $this->modified_date = date('Y-m-d H:i:s');
            $this->updated_at = time();
            $this->modified_by = !Yii::$app->user->isGuest ? Yii::$app->user->identity->id : $this->id;
        }
        parent::beforeSave($insert);
        return true;
    }

}

/* SENDMAIL */