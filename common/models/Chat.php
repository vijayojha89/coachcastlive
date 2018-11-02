<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "chat".
 *
 * @property integer $chat_id
 * @property integer $question_id
 * @property integer $sender_id
 * @property integer $receiver_id
 * @property integer $message_type
 * @property string $message
 * @property integer $receiver_seen
 * @property integer $owner_del
 * @property integer $receiver_del
 * @property string $file_original_name
 * @property string $file_name
 * @property integer $marked_answer_tutor
 * @property integer $accepted_answer_student
 * @property integer $created_by
 * @property string $created_date
 * @property integer $status
 */
class Chat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['question_id', 'sender_id', 'receiver_id', 'message_type', 'message', 'created_by', 'created_date'], 'required'],
            [['question_id', 'sender_id', 'receiver_id', 'message_type', 'receiver_seen', 'owner_del', 'receiver_del', 'marked_answer_tutor', 'accepted_answer_student', 'created_by', 'status'], 'integer'],
            [['message'], 'string'],
            [['created_date'], 'safe'],
            [['file_original_name', 'file_name'], 'string', 'max' => 500],
            [['chat_id'], 'required', 'on' => 'markAnswer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'chat_id' => Yii::t('app', 'Chat ID'),
            'question_id' => Yii::t('app', 'Question ID'),
            'sender_id' => Yii::t('app', 'Sender ID'),
            'receiver_id' => Yii::t('app', 'Receiver ID'),
            'message_type' => Yii::t('app', 'Message Type'),
            'message' => Yii::t('app', 'Message'),
            'receiver_seen' => Yii::t('app', 'Receiver Seen'),
            'owner_del' => Yii::t('app', 'Owner Del'),
            'receiver_del' => Yii::t('app', 'Receiver Del'),
            'file_original_name' => Yii::t('app', 'File Original Name'),
            'file_name' => Yii::t('app', 'File Name'),
            'marked_answer_tutor' => Yii::t('app', 'Marked Answer Tutor'),
            'accepted_answer_student' => Yii::t('app', 'Accepted Answer Student'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_date' => Yii::t('app', 'Created Date'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
