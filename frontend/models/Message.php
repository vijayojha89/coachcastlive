<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property integer $message_id
 * @property integer $from_id
 * @property integer $to_id
 * @property integer $reply
 * @property integer $is_read
 * @property string $message_text
 * @property integer $status
 * @property string $created_date
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_id', 'to_id', 'message_text'], 'required'],
            [['from_id', 'to_id', 'reply', 'is_read', 'status'], 'integer'],
            [['message_text'], 'string'],
            [['created_date','conversation_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message_id' => 'Message ID',
            'from_id' => 'From ID',
            'to_id' => 'To ID',
            'reply' => 'Reply',
            'is_read' => 'Is Read',
            'message_text' => 'Message Text',
            'status' => 'Status',
            'created_date' => 'Created Date',
        ];
    }
}
