<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subject".
 *
 * @property integer $subject_id
 * @property string $name
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 * @property integer $status
 */
class Subject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','status'], 'required'],
            [['created_by', 'status'], 'integer'],
            [['created_date', 'modified_date','request_status','request_type'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $name = 'Name';
        if(Yii::$app->controller->action->id == 'request-expertise'){$name = 'Expertise Name';}
        return [
            'subject_id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', $name),
            'created_by' => Yii::t('app', 'Created By'),
            'created_date' => Yii::t('app', 'Created Date'),
            'modified_date' => Yii::t('app', 'Modified Date'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
