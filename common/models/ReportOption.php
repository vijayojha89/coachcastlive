<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "report_option".
 *
 * @property integer $report_option_id
 * @property string $option
 * @property string $role
 * @property integer $status
 */
class ReportOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report_option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option', 'role'], 'required'],
            [['role'], 'string'],
            [['status'], 'integer'],
            [['option'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'report_option_id' => Yii::t('app', 'Report Option ID'),
            'option' => Yii::t('app', 'Option'),
            'role' => Yii::t('app', 'Role'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
