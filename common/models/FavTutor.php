<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "fav_tutor".
 *
 * @property integer $fav_tutor_id
 * @property integer $student_id
 * @property integer $tutor_id
 * @property string $created_date
 */
class FavTutor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fav_tutor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id', 'tutor_id'], 'required'],
            [['student_id', 'tutor_id'], 'integer'],
            [['created_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fav_tutor_id' => Yii::t('app', 'Fav Tutor ID'),
            'student_id' => Yii::t('app', 'Student ID'),
            'tutor_id' => Yii::t('app', 'Tutor ID'),
            'created_date' => Yii::t('app', 'Created Date'),
        ];
    }
}
