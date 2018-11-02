<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property integer $transaction_id
 * @property integer $user_id
 * @property integer $question_id
 * @property string $stripe_charge_id
 * @property string $stripe_card_id
 * @property double $amount
 * @property double $amount_refunded
 * @property string $balance_transaction
 * @property string $captured
 * @property string $stripe_customer_id
 * @property string $failure_code
 * @property string $failure_message
 * @property string $paid
 * @property integer $created_by
 * @property string $created_date
 * @property string $modified_date
 * @property integer $status
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'question_id', 'created_by', 'status'], 'integer'],
            [['amount', 'amount_refunded'], 'number'],
            [['created_date', 'modified_date'], 'safe'],
            [['fitmakers_txn_id', 'stripe_charge_id', 'stripe_card_id', 'balance_transaction', 'stripe_customer_id', 'failure_code', 'failure_message'], 'string', 'max' => 500],
            [['captured', 'paid'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transaction_id' => 'Transaction ID',
            'fitmakers_txn_id' => 'FitMakersLive Txn ID',
            'user_id' => 'User ID',
            'question_id' => 'Question ID',
            'stripe_charge_id' => 'Stripe Charge ID',
            'stripe_card_id' => 'Stripe Card ID',
            'amount' => 'Amount',
            'amount_refunded' => 'Amount Refunded',
            'balance_transaction' => 'Balance Transaction',
            'captured' => 'Captured',
            'stripe_customer_id' => 'Stripe Customer ID',
            'failure_code' => 'Failure Code',
            'failure_message' => 'Failure Message',
            'paid' => 'Paid',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'modified_date' => 'Modified Date',
            'status' => 'Status',
        ];
    }
}
