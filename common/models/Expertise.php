<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "expertise".
 *
 * @property integer $expertise_id
 * @property string $name
 * @property integer $created_by
 * @property string $created_date
 * @property string $modified_date
 * @property integer $status
 */
class Expertise extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'expertise';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','status'], 'required'],
            [['created_by', 'status'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'expertise_id' => Yii::t('app', 'Expertise ID'),
            'name' => Yii::t('app', 'Name'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_date' => Yii::t('app', 'Created Date'),
            'modified_date' => Yii::t('app', 'Modified Date'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
