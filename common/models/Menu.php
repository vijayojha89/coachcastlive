<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property integer $menu_id
 * @property string $menu_name
 * @property string $menu_image
 * @property integer $status
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'menu_name'], 'required'],
            [['status', 'created_by', 'modified_by'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [[ 'menu_name'], 'string', 'max' => 50],
            [['menu_image', 'menu_background_image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menu_id' => Yii::t('app', 'Menu ID'),
            'menu_name' => Yii::t('app', 'Menu Name'),
            'menu_image' => Yii::t('app', 'Menu Image'),
            'menu_background_image' => Yii::t('app', 'Background Image'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_date' => Yii::t('app', 'Created Date'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_date' => Yii::t('app', 'Modified Date'),
        ];
    }
}
