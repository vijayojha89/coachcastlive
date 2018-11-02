<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "review".
 *
 * @property integer $review_id
 * @property integer $review_opt
 * @property string $comment
 * @property integer $rating
 * @property string $created_date
 * @property integer $question_id
 * @property integer $posted_by
 * @property integer $posted_for
 * @property string $posted_by_role
 * @property string $posted_for_role
 * @property integer $status
 */
class Review extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'review';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['review_opt', 'comment', 'rating', 'question_id', 'posted_by', 'posted_for', 'posted_by_role', 'posted_for_role'], 'required'],
            [['review_opt', 'rating', 'question_id', 'posted_by', 'posted_for', 'status'], 'integer'],
            [['comment'], 'string'],
            [['created_date'], 'safe'],
            [['posted_by_role', 'posted_for_role'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'review_id' => Yii::t('app', 'Review ID'),
            'review_opt' => Yii::t('app', 'Review Option'),
            'comment' => Yii::t('app', 'Comment'),
            'rating' => Yii::t('app', 'Rating'),
            'created_date' => Yii::t('app', 'Created Date'),
            'question_id' => Yii::t('app', 'Question ID'),
            'posted_by' => Yii::t('app', 'Posted By'),
            'posted_for' => Yii::t('app', 'User Name'),
            'posted_by_role' => Yii::t('app', 'Posted By Role'),
            'posted_for_role' => Yii::t('app', 'Posted For Role'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
