<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "email_template".
 *
 * @property integer $emailtemplate_id
 * @property string $emailtemplate_name
 * @property string $emailtemplate_subject
 * @property string $emailtemplate_body
 * @property integer $status
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 */
class EmailTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['emailtemplate_name', 'emailtemplate_subject', 'emailtemplate_body'], 'required'],
            [['emailtemplate_subject', 'emailtemplate_body'], 'string'],
            [['status'], 'integer'],
            [[ 'created_by', 'created_date', 'modified_by', 'modified_date'], 'safe'],
            [['emailtemplate_name', 'emailtemplate_subject'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'emailtemplate_id' => Yii::t('app', 'ID'),
            'emailtemplate_name' => Yii::t('app', 'Name'),
            'emailtemplate_subject' => Yii::t('app', 'Subject'),
            'emailtemplate_body' => Yii::t('app', 'Body'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_date' => Yii::t('app', 'Created Date'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_date' => Yii::t('app', 'Modified Date'),
        ];
    }
}
