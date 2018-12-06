<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "trainer_class".
 *
 * @property integer $trainer_class_id
 * @property string $title
 * @property string $description
 * @property integer $workout_type_id
 * @property double $price
 * @property string $class_image
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 * @property integer $status
 * @property string $start_date
 * @property string $end_date
 * @property string $time
 */
class TrainerClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trainer_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'string'],
            [['workout_type_id', 'created_by','start_date','end_date','time','title','price'], 'required'],
            [['workout_type_id', 'created_by', 'modified_by', 'status'], 'integer'],
            [['price'], 'number','min' => 1],
            [['created_date', 'modified_date', 'start_date', 'end_date', 'time'], 'safe'],
            [['class_image'], 'string', 'max' => 255],
            [['class_image'],'file' ,'extensions'=>'jpg, jpeg, png, bmp'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'trainer_class_id' => 'Trainer Class ID',
            'title' => 'Title',
            'description' => 'Description',
            'workout_type_id' => 'Workout Type',
            'price' => 'Price',
            'class_image' => 'Class Image',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
            'status' => 'Status',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'time' => 'Time',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
