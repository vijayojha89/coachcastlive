<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "roles_allocation".
 *
 * @property integer $id
 * @property integer $role_id
 * @property integer $controller_id
 * @property integer $action_id
 * @property integer $status
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 */
class Rolesallocation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'roles_allocation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'controller_id', 'action_id', 'status', 'created_by', 'created_date'], 'required'],
            [['role_id', 'controller_id', 'action_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'controller_id' => 'Controller ID',
            'action_id' => 'Action ID',
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
        ];
    }
}
