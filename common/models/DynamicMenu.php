<?php


namespace common\models;
use Yii;

/**
 * This is the model class for table "dynamic_menu".
 *
 * @property integer $id
 * @property integer $controller_id
 * @property string $label
 * @property string $url
 * @property string $index_array
 * @property integer $sort
 * @property string $icon
 * @property string $key
 * @property string $description
 * @property integer $status
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 */
class DynamicMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dynamic_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['controller_id', 'sort', 'status', 'created_by', 'modified_by'], 'integer'],
            [['controller_id','label','url', 'key', 'icon','type','actions'], 'required','on'=>['menuCreate','menuUpdate']],
            [['controller_id','label','url', 'key', 'icon','menu_id','actions'], 'required','on'=>['menuAdd','menuUpdatedd']],
            [['created_date', 'modified_date','type','menu_id','actions'], 'safe'],
            [['label', 'url', 'icon'], 'string', 'max' => 100],
            [['index_array', 'key'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'controller_id' => Yii::t('app', 'Controller'),
            'label' => Yii::t('app', 'Label'),
            'url' => Yii::t('app', 'Url'),
            'index_array' => Yii::t('app', 'Index Array'),
            'sort' => Yii::t('app', 'Sort'),
            'icon' => Yii::t('app', 'Icon'),
            'key' => Yii::t('app', 'Key'),
            'description' => Yii::t('app', 'Description'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_date' => Yii::t('app', 'Created Date'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_date' => Yii::t('app', 'Modified Date'),
        ];
    }
}
