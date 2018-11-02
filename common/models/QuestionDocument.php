<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "question_document".
 *
 * @property integer $question_document_id
 * @property integer $question_id
 * @property string $document_name
 * @property integer $created_by
 * @property string $created_date
 * @property string $modified_date
 * @property integer $status
 */
class QuestionDocument extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question_document';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id'], 'required'],
            [['question_id', 'created_by', 'status'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['document_name','original_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'question_document_id' => Yii::t('app', 'Question Document ID'),
            'question_id' => Yii::t('app', 'Question ID'),
            'document_name' => Yii::t('app', 'Document Name'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_date' => Yii::t('app', 'Created Date'),
            'modified_date' => Yii::t('app', 'Modified Date'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
