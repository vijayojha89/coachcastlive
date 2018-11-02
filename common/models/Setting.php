<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "setting".
 *
 * @property integer $setting_id
 * @property string $setting_google
 * @property string $setting_facebook
 * @property string $setting_linkedin
 * @property string $setting_yahoo
 * @property string $setting_logo_image
 * @property string $setting_favicon_image
 * @property integer $status
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_by', 'modified_by','is_push_notification','chat_session_timeout','student_accept_answer_timeout'], 'integer'],
            [['manage_commission','referral_commission'], 'number'],
            [['chat_session_timeout', 'student_accept_answer_timeout','setting_facebook','setting_instagram','setting_twitter','stripe_payment'], 'required'],
            [['setting_id','created_date', 'modified_date','manage_commission','stripe_payment','referral_commission'], 'safe'],
            ['manage_commission', 'compare', 'compareValue' => 100, 'operator' => '<='],
            ['referral_commission', 'compare', 'compareValue' => 100, 'operator' => '<='],
            [['student_accept_answer_timeout'], 'compare', 'compareValue' => 0, 'operator' => '>'],
            [['chat_session_timeout'], 'compare', 'compareValue' => 48, 'operator' => '>'],
            [['setting_logo_image', 'setting_favicon_image','push_notification_ios','push_notification_android','s3_region','s3_key','s3_secret','s3_defaultBucket'], 'string', 'max' => 500],
            [['setting_google', 'setting_facebook', 'setting_instagram', 'setting_twitter'], 'string', 'max' => 1000],
            [['refer_step1','refer_step2','refer_step3'],'file' ,'extensions'=>['jpg', 'jpeg', 'png','bmp']],
            [['setting_facebook','setting_instagram','setting_twitter'],'url','message'=>'Url not valid.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'setting_id' => Yii::t('app', 'Setting ID'),
            'setting_google' => Yii::t('app', 'Setting Google'),
            'setting_facebook' => Yii::t('app', 'Facebook Link'),
            'setting_instagram' => Yii::t('app', 'Instagram Link'),
            'setting_twitter' => Yii::t('app', 'Twitter Link'),
            'setting_logo_image' => Yii::t('app', 'Logo Image'),
            'setting_favicon_image' => Yii::t('app', 'Favicon Image'),
            'is_push_notification' => Yii::t('app', 'Enable Pushnotifications'),
            'manage_commission' => Yii::t('app', 'Admin Commission(%)'),
            'referral_commission' => Yii::t('app', 'Referral Commission(%)'),
            'push_notification_ios' => Yii::t('app', 'Pem File For Pushnotification'),
            'push_notification_ios_password' => Yii::t('app', 'Ios Key For Pushnotification'),
            'push_notification_android' => Yii::t('app', 'Android Key For Pushnotifiation'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_date' => Yii::t('app', 'Created Date'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_date' => Yii::t('app', 'Modified Date'),
            'chat_session_timeout' => Yii::t('app', 'Hour For Chat Session Timeout'),
            'student_accept_answer_timeout' => Yii::t('app', 'Hour For Student Accept Answer Timeout'),
            'refer_step1' => Yii::t('app', 'Step 1'),
            'refer_step2' => Yii::t('app', 'Step 2'),
            'refer_step3' => Yii::t('app', 'Step 3'),
        ];
    }
}
