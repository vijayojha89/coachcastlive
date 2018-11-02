<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "workout_type".
 *
 * @property integer $workout_type_id
 * @property string $name
 * @property integer $parent
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 * @property integer $status
 */
class WorkoutType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'workout_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['parent', 'created_by', 'modified_by', 'status'], 'integer'],
            [['created_by', 'modified_date'], 'required'],
            [['created_date', 'modified_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'workout_type_id' => 'Workout Type ID',
            'name' => 'Name',
            'parent' => 'Parent',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
            'status' => 'Status',
        ];
    }
}
