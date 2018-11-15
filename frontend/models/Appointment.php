<?php

namespace frontend\models;

use Yii;

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
 * @property string $completed_date
 * @property string $created_date
 * @property string $modified_date
 * @property integer $status
 * @property integer $question_status
 */
class Appointment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'appointment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'reason', 'created_by', 'appointment_date','trainer_id'], 'required'],
            [['reason'], 'string'],
            [['trainer_id'], 'integer'],
            [['price'], 'number'],
            [['appointment_date','created_date', 'modified_date'], 'safe'],
            [['title'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'appointment_id' => 'Appointment ID',
            'title' => 'Title',
            'reason' => 'Reason',
            'price' => 'Price',
            'created_by' => 'Created By',
            'appointment_date' => 'Appointment Date',
            'created_date' => 'Created Date',
            'modified_date' => 'Modified Date',
            'status' => 'Status',
            'appointment_status' => 'Appointment Status',
        ];
    }
    
    public function getTrainer()
    {
        return $this->hasOne(User::className(), ['id' => 'trainer_id']);
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
