<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ask_agin_questions_log".
 *
 * @property integer $ask_agin_questions_log_id
 * @property integer $question_id
 * @property string $asked_date
 */
class AskAginQuestionsLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ask_agin_questions_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'asked_date'], 'required'],
            [['question_id'], 'integer'],
            [['asked_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ask_agin_questions_log_id' => Yii::t('app', 'Ask Agin Questions Log ID'),
            'question_id' => Yii::t('app', 'Question ID'),
            'asked_date' => Yii::t('app', 'Asked Date'),
        ];
    }
}
