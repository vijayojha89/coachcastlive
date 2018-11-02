<?php

namespace common\models;

use Yii;
use common\components\GeneralComponent;

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
class ChangePassword extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $old_password;
    public $confirm_password;
    
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
   public function rules()
    {
       return [
            [['old_password','password_hash', 'confirm_password'], 'required'],
            [['confirm_password', 'password_hash'], 'string', 'min' => 8],
            [['confirm_password'], 'compare', 'compareAttribute' => 'password_hash', 'skipOnEmpty' => false,'message' => "Password's do not match."],
        ];
    }
    
    
    public function attributeLabels() {
        return [
            'password_hash' => 'New Password',
            'old_password' => 'Current Password',
        ];
    }
    
}
