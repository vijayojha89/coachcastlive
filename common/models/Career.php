<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "career".
 *
 * @property integer $career_id
 * @property integer $department
 * @property string $title
 * @property string $location
 * @property string $created_date
 * @property string $modified_date
 * @property integer $created_by
 * @property integer $status
 */
class Career extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'career';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['department', 'title', 'location'], 'required'],
            [['department', 'created_by', 'status'], 'integer'],
            [['created_date', 'modified_date','description'], 'safe'],
            [['title', 'location'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'career_id' => Yii::t('app', 'Career ID'),
            'department' => Yii::t('app', 'Department'),
            'title' => Yii::t('app', 'Title'),
            'location' => Yii::t('app', 'Location'),
            'created_date' => Yii::t('app', 'Created Date'),
            'modified_date' => Yii::t('app', 'Modified Date'),
            'created_by' => Yii::t('app', 'Created By'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
