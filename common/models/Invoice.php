<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property integer $invoice_id
 * @property string $zoho_invoice_id
 * @property string $zoho_invoice_number
 * @property string $zoho_salesorder_id
 * @property string $zoho_ach_payment_initiated
 * @property string $zoho_zcrm_potential_id
 * @property string $zoho_zcrm_potential_name
 * @property string $zoho_date
 * @property string $zoho_status
 * @property string $zoho_payment_terms
 * @property string $zoho_payment_terms_label
 * @property string $zoho_due_date
 * @property string $zoho_payment_expected_date
 * @property double $zoho_payment_discount
 * @property string $zoho_stop_reminder_until_payment_expected_date
 * @property string $zoho_last_payment_date
 * @property string $zoho_reference_number
 * @property string $zoho_customer_id
 * @property string $zoho_estimate_id
 * @property string $zoho_is_client_review_settings_enabled
 * @property string $zoho_customer_name
 * @property string $zoho_unused_retainer_payments
 * @property string $zoho_contact_persons
 * @property string $zoho_currency_id
 * @property string $zoho_currency_code
 * @property string $zoho_currency_symbol
 * @property string $zoho_exchange_rate
 * @property double $zoho_discount
 * @property string $zoho_discount_applied_on_amount
 * @property string $zoho_is_discount_before_tax
 * @property string $zoho_discount_type
 * @property string $zoho_recurring_invoice_id
 * @property string $zoho_documents
 * @property string $zoho_is_viewed_by_client
 * @property string $zoho_client_viewed_time
 * @property string $zoho_is_inclusive_tax
 * @property string $zoho_schedule_time
 * @property string $zoho_line_items
 * @property string $zoho_contact_persons_details
 * @property double $zoho_shipping_charge
 * @property string $zoho_adjustment
 * @property string $zoho_adjustment_description
 * @property string $zoho_late_fee
 * @property double $zoho_sub_total
 * @property double $zoho_tax_total
 * @property double $zoho_total
 * @property string $zoho_taxes
 * @property string $zoho_payment_reminder_enabled
 * @property string $zoho_payment_made
 * @property string $zoho_credits_applied
 * @property string $zoho_tax_amount_withheld
 * @property double $zoho_balance
 * @property string $zoho_write_off_amount
 * @property string $zoho_allow_partial_payments
 * @property integer $zoho_price_precision
 * @property string $zoho_payment_options
 * @property string $zoho_is_emailed
 * @property string $zoho_reminders_sent
 * @property string $zoho_last_reminder_sent_date
 * @property string $zoho_next_reminder_date_formatted
 * @property string $zoho_billing_address
 * @property string $zoho_shipping_address
 * @property string $zoho_notes
 * @property string $zoho_terms
 * @property string $zoho_custom_fields
 * @property string $zoho_custom_field_hash
 * @property string $zoho_template_id
 * @property string $zoho_template_name
 * @property string $zoho_template_type
 * @property string $zoho_page_width
 * @property string $zoho_page_height
 * @property string $zoho_orientation
 * @property string $zoho_created_time
 * @property string $zoho_last_modified_time
 * @property string $zoho_created_by_id
 * @property string $zoho_attachment_name
 * @property string $zoho_can_send_in_mail
 * @property string $zoho_salesperson_id
 * @property string $zoho_salesperson_name
 * @property string $zoho_is_autobill_enabled
 * @property string $zoho_invoice_url
 * @property integer $question_id
 * @property integer $transaction_id
 * @property integer $tutor_id
 * @property integer $student_id
 * @property integer $created_by
 * @property string $created_date
 * @property string $modified_date
 * @property integer $status
 * @property integer $user_id
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['zoho_date', 'zoho_due_date', 'created_date', 'modified_date'], 'safe'],
            [['zoho_payment_discount', 'zoho_discount', 'zoho_shipping_charge', 'zoho_sub_total', 'zoho_tax_total', 'zoho_total', 'zoho_balance'], 'number'],
            [['zoho_contact_persons', 'zoho_documents', 'zoho_line_items', 'zoho_contact_persons_details', 'zoho_adjustment_description', 'zoho_late_fee', 'zoho_taxes', 'zoho_payment_options', 'zoho_billing_address', 'zoho_shipping_address', 'zoho_notes', 'zoho_custom_fields', 'zoho_custom_field_hash', 'zoho_template_name', 'zoho_attachment_name', 'zoho_invoice_url'], 'string'],
            [['zoho_shipping_charge', 'user_id'], 'required'],
            [['zoho_price_precision', 'question_id', 'transaction_id', 'tutor_id', 'student_id', 'created_by', 'status', 'user_id'], 'integer'],
            [['zoho_invoice_id', 'zoho_invoice_number', 'zoho_payment_expected_date', 'zoho_stop_reminder_until_payment_expected_date', 'zoho_last_payment_date', 'zoho_reference_number', 'zoho_customer_id', 'zoho_customer_name', 'zoho_currency_id', 'zoho_last_reminder_sent_date', 'zoho_created_time', 'zoho_last_modified_time', 'zoho_salesperson_id', 'zoho_salesperson_name'], 'string', 'max' => 500],
            [['zoho_salesorder_id', 'zoho_ach_payment_initiated', 'zoho_zcrm_potential_id', 'zoho_zcrm_potential_name', 'zoho_status', 'zoho_payment_terms', 'zoho_estimate_id', 'zoho_schedule_time', 'zoho_adjustment', 'zoho_credits_applied', 'zoho_tax_amount_withheld', 'zoho_write_off_amount', 'zoho_page_width', 'zoho_page_height'], 'string', 'max' => 50],
            [['zoho_payment_terms_label', 'zoho_template_type', 'zoho_created_by_id'], 'string', 'max' => 255],
            [['zoho_is_client_review_settings_enabled', 'zoho_unused_retainer_payments', 'zoho_currency_code', 'zoho_currency_symbol', 'zoho_exchange_rate', 'zoho_discount_applied_on_amount', 'zoho_is_discount_before_tax', 'zoho_is_viewed_by_client', 'zoho_is_inclusive_tax', 'zoho_payment_reminder_enabled', 'zoho_payment_made', 'zoho_is_emailed', 'zoho_reminders_sent', 'zoho_terms', 'zoho_can_send_in_mail', 'zoho_is_autobill_enabled'], 'string', 'max' => 10],
            [['zoho_discount_type', 'zoho_recurring_invoice_id', 'zoho_client_viewed_time', 'zoho_allow_partial_payments', 'zoho_next_reminder_date_formatted', 'zoho_template_id', 'zoho_orientation'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoice_id' => 'Invoice ID',
            'zoho_invoice_id' => 'Invoice ID',
            'zoho_invoice_number' => 'Invoice Number',
            'zoho_salesorder_id' => 'Zoho Salesorder ID',
            'zoho_ach_payment_initiated' => 'Zoho Ach Payment Initiated',
            'zoho_zcrm_potential_id' => 'Zoho Zcrm Potential ID',
            'zoho_zcrm_potential_name' => 'Zoho Zcrm Potential Name',
            'zoho_date' => 'Date',
            'zoho_status' => 'Status',
            'zoho_payment_terms' => 'Zoho Payment Terms',
            'zoho_payment_terms_label' => 'Zoho Payment Terms Label',
            'zoho_due_date' => 'Zoho Due Date',
            'zoho_payment_expected_date' => 'Zoho Payment Expected Date',
            'zoho_payment_discount' => 'Zoho Payment Discount',
            'zoho_stop_reminder_until_payment_expected_date' => 'Zoho Stop Reminder Until Payment Expected Date',
            'zoho_last_payment_date' => 'Zoho Last Payment Date',
            'zoho_reference_number' => 'Zoho Reference Number',
            'zoho_customer_id' => 'Zoho Customer ID',
            'zoho_estimate_id' => 'Zoho Estimate ID',
            'zoho_is_client_review_settings_enabled' => 'Zoho Is Client Review Settings Enabled',
            'zoho_customer_name' => 'Zoho Customer Name',
            'zoho_unused_retainer_payments' => 'Zoho Unused Retainer Payments',
            'zoho_contact_persons' => 'Zoho Contact Persons',
            'zoho_currency_id' => 'Zoho Currency ID',
            'zoho_currency_code' => 'Zoho Currency Code',
            'zoho_currency_symbol' => 'Zoho Currency Symbol',
            'zoho_exchange_rate' => 'Zoho Exchange Rate',
            'zoho_discount' => 'Zoho Discount',
            'zoho_discount_applied_on_amount' => 'Zoho Discount Applied On Amount',
            'zoho_is_discount_before_tax' => 'Zoho Is Discount Before Tax',
            'zoho_discount_type' => 'Zoho Discount Type',
            'zoho_recurring_invoice_id' => 'Zoho Recurring Invoice ID',
            'zoho_documents' => 'Zoho Documents',
            'zoho_is_viewed_by_client' => 'Zoho Is Viewed By Client',
            'zoho_client_viewed_time' => 'Zoho Client Viewed Time',
            'zoho_is_inclusive_tax' => 'Zoho Is Inclusive Tax',
            'zoho_schedule_time' => 'Zoho Schedule Time',
            'zoho_line_items' => 'Zoho Line Items',
            'zoho_contact_persons_details' => 'Zoho Contact Persons Details',
            'zoho_shipping_charge' => 'Zoho Shipping Charge',
            'zoho_adjustment' => 'Zoho Adjustment',
            'zoho_adjustment_description' => 'Zoho Adjustment Description',
            'zoho_late_fee' => 'Zoho Late Fee',
            'zoho_sub_total' => 'Zoho Sub Total',
            'zoho_tax_total' => 'Zoho Tax Total',
            'zoho_total' => 'Total',
            'zoho_taxes' => 'Zoho Taxes',
            'zoho_payment_reminder_enabled' => 'Zoho Payment Reminder Enabled',
            'zoho_payment_made' => 'Zoho Payment Made',
            'zoho_credits_applied' => 'Zoho Credits Applied',
            'zoho_tax_amount_withheld' => 'Zoho Tax Amount Withheld',
            'zoho_balance' => 'Zoho Balance',
            'zoho_write_off_amount' => 'Zoho Write Off Amount',
            'zoho_allow_partial_payments' => 'Zoho Allow Partial Payments',
            'zoho_price_precision' => 'Zoho Price Precision',
            'zoho_payment_options' => 'Zoho Payment Options',
            'zoho_is_emailed' => 'Zoho Is Emailed',
            'zoho_reminders_sent' => 'Zoho Reminders Sent',
            'zoho_last_reminder_sent_date' => 'Zoho Last Reminder Sent Date',
            'zoho_next_reminder_date_formatted' => 'Zoho Next Reminder Date Formatted',
            'zoho_billing_address' => 'Zoho Billing Address',
            'zoho_shipping_address' => 'Zoho Shipping Address',
            'zoho_notes' => 'Zoho Notes',
            'zoho_terms' => 'Zoho Terms',
            'zoho_custom_fields' => 'Zoho Custom Fields',
            'zoho_custom_field_hash' => 'Zoho Custom Field Hash',
            'zoho_template_id' => 'Zoho Template ID',
            'zoho_template_name' => 'Zoho Template Name',
            'zoho_template_type' => 'Zoho Template Type',
            'zoho_page_width' => 'Zoho Page Width',
            'zoho_page_height' => 'Zoho Page Height',
            'zoho_orientation' => 'Zoho Orientation',
            'zoho_created_time' => 'Zoho Created Time',
            'zoho_last_modified_time' => 'Zoho Last Modified Time',
            'zoho_created_by_id' => 'Zoho Created By ID',
            'zoho_attachment_name' => 'Zoho Attachment Name',
            'zoho_can_send_in_mail' => 'Zoho Can Send In Mail',
            'zoho_salesperson_id' => 'Zoho Salesperson ID',
            'zoho_salesperson_name' => 'Zoho Salesperson Name',
            'zoho_is_autobill_enabled' => 'Zoho Is Autobill Enabled',
            'zoho_invoice_url' => 'Zoho Invoice Url',
            'question_id' => 'Question ID',
            'transaction_id' => 'Transaction ID',
            'tutor_id' => 'Tutor ID',
            'student_id' => 'Student ID',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'modified_date' => 'Modified Date',
            'status' => 'Status',
            'user_id' => 'User',
        ];
    }
}
