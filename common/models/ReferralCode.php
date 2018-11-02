<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "referral_code".
 *
 * @property integer $referral_code_id
 * @property integer $referral_user_id
 * @property integer $referral_owner_id
 * @property string $referral_code
 */
class ReferralCode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'referral_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['referral_user_id', 'referral_owner_id', 'referral_code'], 'required'],
            [['referral_user_id', 'referral_owner_id'], 'integer'],
            [['referral_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'referral_code_id' => Yii::t('app', 'Referral Code'),
            'referral_user_id' => Yii::t('app', 'Referral User'),
            'referral_owner_id' => Yii::t('app', 'Referral Owner'),
            'referral_code' => Yii::t('app', 'Referral Code'),
        ];
    }
}
