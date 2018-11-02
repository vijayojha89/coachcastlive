<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "blog".
 *
 * @property integer $blog_id
 * @property string $title
 * @property string $description
 * @property string $blog_image
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 * @property integer $status
 */
class Blog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'string'],
            [['description', 'created_by'], 'required'],
            [['created_by', 'modified_by', 'status'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['blog_image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'blog_id' => 'Blog ID',
            'title' => 'Title',
            'description' => 'Description',
            'blog_image' => 'Blog Image',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
            'status' => 'Status',
        ];
    }
}
