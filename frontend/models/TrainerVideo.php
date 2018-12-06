<?php

namespace frontend\models;

use Yii;
use common\components\GeneralComponent;

/**
 * This is the model class for table "trainer_video".
 *
 * @property integer $trainer_video_id
 * @property string $title
 * @property string $description
 * @property integer $workout_type_id
 * @property double $price
 * @property string $video_image
 * @property string $video_file
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 * @property integer $status
 */
class TrainerVideo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trainer_video';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','description'], 'string'],
            [['title','description','workout_type_id', 'created_by'], 'required'],
            [['workout_type_id', 'created_by', 'modified_by', 'status','no_of_view'], 'integer'],
            [['price'], 'number'],
            [['created_date', 'modified_date'], 'safe'],
            [['video_image', 'video_file'], 'string', 'max' => 255],
            [['video_image'],'file' ,'extensions'=>'jpg, jpeg, png, bmp'],
            [['video_file'],'file' ,'extensions'=>'mp4'],
        ];
    }   

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'trainer_video_id' => 'Trainer Video ID',
            'title' => 'Title',
            'description' => 'Description',
            'workout_type_id' => 'Workout Type',
            'price' => 'Price',
            'video_image' => 'Video Image',
            'video_file' => 'Video File',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
            'status' => 'Status',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
