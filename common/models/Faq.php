<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "faq".
 *
 * @property integer $faq_id
 * @property string $title
 * @property string $content
 * @property string $created_date
 * @property string $modified_date
 * @property integer $status
 */
class Faq extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'faq';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['content'], 'string'],
            [['created_date', 'modified_date'], 'safe'],
            [['status'], 'integer'],
            [['title'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'faq_id' => Yii::t('app', 'Faq ID'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'created_date' => Yii::t('app', 'Created Date'),
            'modified_date' => Yii::t('app', 'Modified Date'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
