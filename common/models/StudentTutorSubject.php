<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "student_tutor_subject".
 *
 * @property integer $student_tutor_subject_id
 * @property integer $user_id
 * @property integer $subject_id
 * @property integer $status
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 */
class StudentTutorSubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_tutor_subject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'subject_id'], 'required'],
            [['user_id', 'subject_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'student_tutor_subject_id' => Yii::t('app', 'Student Tutor Subject ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'subject_id' => Yii::t('app', 'Subject ID'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_date' => Yii::t('app', 'Created Date'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_date' => Yii::t('app', 'Modified Date'),
        ];
    }
}
