<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "notification_generalization".
 *
 * @property integer $notification_generalization_id
 * @property integer $notification_from
 * @property integer $notification_to
 * @property integer $notification_type
 * @property string $notification_text
 * @property integer $is_read
 * @property integer $status
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 */
class NotificationGeneralization extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_generalization';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coachcastlive_id', 'notification_from', 'notification_to', 'notification_type', 'is_read', 'status', 'created_by', 'modified_by'], 'integer'],
            [['notification_from', 'notification_to', 'notification_type', 'notification_text'], 'required'],
            [['created_date', 'modified_date'], 'safe'],
            [['notification_text'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'notification_generalization_id' => Yii::t('app', 'Notification Generalization ID'),
            'coachcastlive_id' => Yii::t('app', 'CoachCastLive ID'),
            'notification_from' => Yii::t('app', 'Notification From'),
            'notification_to' => Yii::t('app', 'Notification To'),
            'notification_type' => Yii::t('app', 'Notification Type'),
            'notification_text' => Yii::t('app', 'Notification Text'),
            'is_read' => Yii::t('app', 'Is Read'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_date' => Yii::t('app', 'Created Date'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_date' => Yii::t('app', 'Modified Date'),
        ];
    }
}
