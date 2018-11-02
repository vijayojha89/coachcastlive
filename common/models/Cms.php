<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cms".
 *
 * @property integer $cms_id
 * @property string $title
 * @property string $content
 * @property string $page_key
 * @property integer $created_by
 * @property string $created_date
 * @property string $modified_date
 * @property integer $status
 */
class Cms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['content'], 'string'],
            [['created_by', 'status'], 'integer'],
            [['created_date', 'modified_date','header_image'], 'safe'],
            [['title', 'page_key'], 'string', 'max' => 255],
            [['header_image'],'file' ,'extensions'=>'jpg, jpeg, png, bmp'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cms_id' => Yii::t('app', 'Cms ID'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'page_key' => Yii::t('app', 'Page Key'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_date' => Yii::t('app', 'Created Date'),
            'modified_date' => Yii::t('app', 'Modified Date'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
