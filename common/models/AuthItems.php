<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "auth_items".
 *
 * @property integer $auth_items_id
 * @property integer $auth_items_name
 * @property integer $auth_items_type
 * @property integer $auth_items_action
 * @property integer $status
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 */
class AuthItems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auth_items_name', 'auth_items_type', 'auth_items_action'], 'required'],
            [['auth_items_name', 'auth_items_type', 'auth_items_action', 'status', 'created_by', 'modified_by'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'auth_items_id' => Yii::t('app', 'Auth Items ID'),
            'auth_items_name' => Yii::t('app', 'Auth Items Name'),
            'auth_items_type' => Yii::t('app', 'Auth Items Type'),
            'auth_items_action' => Yii::t('app', 'Auth Items Action'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_date' => Yii::t('app', 'Created Date'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_date' => Yii::t('app', 'Modified Date'),
        ];
    }
}
