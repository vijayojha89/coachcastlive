<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu_access".
 *
 * @property integer $menu_access_id
 * @property integer $user_id
 * @property string $menu_access_type
 * @property string $menu_access_controller
 * @property integer $status
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 */
class MenuAccess extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_access';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'user_id', 'menu_access_type', 'menu_access_controller'], 'required'],
            [[ 'user_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['menu_access_type'], 'string'],
            [['created_date', 'modified_date'], 'safe'],
            [['menu_access_controller'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menu_access_id' => Yii::t('app', 'Menu Access ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'menu_access_type' => Yii::t('app', 'Menu Access Type'),
            'menu_access_controller' => Yii::t('app', 'Menu Access'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_date' => Yii::t('app', 'Created Date'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_date' => Yii::t('app', 'Modified Date'),
        ];
    }
}
