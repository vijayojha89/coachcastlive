<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "report_abuse".
 *
 * @property integer $report_abuse
 * @property integer $option_id
 * @property string $comment
 * @property integer $reoprt_abuse_by
 * @property integer $report_abuse_for
 * @property string $roport_abuse_by_role
 * @property string $roport_abuse_for_role
 * @property string $created_date
 */
class ReportAbuse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report_abuse';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option_id', 'comment', 'reoprt_abuse_by', 'report_abuse_for', 'roport_abuse_by_role', 'roport_abuse_for_role', 'created_date'], 'required'],
            [['option_id', 'reoprt_abuse_by', 'report_abuse_for','question_id','status'], 'integer'],
            [['created_date','question_id'], 'safe'],
            [['comment', 'roport_abuse_by_role', 'roport_abuse_for_role'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'report_abuse' => Yii::t('app', 'Report Abuse'),
            'option_id' => Yii::t('app', 'Report Option'),
            'comment' => Yii::t('app', 'Comment'),
            'reoprt_abuse_by' => Yii::t('app', 'Reoprt Abuse By'),
            'report_abuse_for' => Yii::t('app', 'Report Abuse For'),
            'roport_abuse_by_role' => Yii::t('app', 'Roport Abuse By Role'),
            'roport_abuse_for_role' => Yii::t('app', 'Roport Abuse For Role'),
            'created_date' => Yii::t('app', 'Created Date'),
        ];
    }
}
