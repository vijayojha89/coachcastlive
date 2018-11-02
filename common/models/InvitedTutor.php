<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "invited_tutor".
 *
 * @property integer $invited_tutor_id
 * @property integer $question_id
 * @property integer $tutor_id
 * @property integer $tutor_requst_status
 * @property integer $student_requst_status
 * @property string $created_date
 * @property string $modified_date
 * @property integer $status
 */
class InvitedTutor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invited_tutor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'tutor_id', 'modified_date'], 'required'],
            [['question_id', 'tutor_id', 'tutor_requst_status', 'student_requst_status','status'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invited_tutor_id' => Yii::t('app', 'Invited Tutor ID'),
            'question_id' => Yii::t('app', 'Question ID'),
            'tutor_id' => Yii::t('app', 'Tutor ID'),
            'tutor_requst_status' => Yii::t('app', 'Tutor Requst Status'),
            'student_requst_status' => Yii::t('app', 'Student Requst Status'),
            'created_date' => Yii::t('app', 'Created Date'),
            'modified_date' => Yii::t('app', 'Modified Date'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
