<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_payment_info".
 *
 * @property integer $user_payment_info_id
 * @property integer $user_id
 * @property string $bank_name
 * @property integer $account_no
 * @property string $sort_code
 * @property string $modified_date
 */
class UserPaymentInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_payment_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'bank_name', 'account_no', 'sort_code'], 'required'],
            [['user_id', 'account_no'], 'integer'],
            [['modified_date'], 'safe'],
            [['bank_name'], 'string', 'max' => 255],
            [['sort_code'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_payment_info_id' => 'User Payment Info ID',
            'user_id' => 'User ID',
            'bank_name' => 'Bank Name',
            'account_no' => 'Account No',
            'sort_code' => 'Sort Code',
            'modified_date' => 'Modified Date',
        ];
    }
}
