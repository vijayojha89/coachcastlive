<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Invoice;

/**
 * InvoiceSearch represents the model behind the search form about `common\models\Invoice`.
 */
class InvoiceSearch extends Invoice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_id', 'zoho_price_precision', 'question_id', 'transaction_id', 'tutor_id', 'student_id', 'created_by', 'status', 'user_id'], 'integer'],
            [['zoho_invoice_id', 'zoho_invoice_number', 'zoho_salesorder_id', 'zoho_ach_payment_initiated', 'zoho_zcrm_potential_id', 'zoho_zcrm_potential_name', 'zoho_date', 'zoho_status', 'zoho_payment_terms', 'zoho_payment_terms_label', 'zoho_due_date', 'zoho_payment_expected_date', 'zoho_stop_reminder_until_payment_expected_date', 'zoho_last_payment_date', 'zoho_reference_number', 'zoho_customer_id', 'zoho_estimate_id', 'zoho_is_client_review_settings_enabled', 'zoho_customer_name', 'zoho_unused_retainer_payments', 'zoho_contact_persons', 'zoho_currency_id', 'zoho_currency_code', 'zoho_currency_symbol', 'zoho_exchange_rate', 'zoho_discount_applied_on_amount', 'zoho_is_discount_before_tax', 'zoho_discount_type', 'zoho_recurring_invoice_id', 'zoho_documents', 'zoho_is_viewed_by_client', 'zoho_client_viewed_time', 'zoho_is_inclusive_tax', 'zoho_schedule_time', 'zoho_line_items', 'zoho_contact_persons_details', 'zoho_adjustment', 'zoho_adjustment_description', 'zoho_late_fee', 'zoho_taxes', 'zoho_payment_reminder_enabled', 'zoho_payment_made', 'zoho_credits_applied', 'zoho_tax_amount_withheld', 'zoho_write_off_amount', 'zoho_allow_partial_payments', 'zoho_payment_options', 'zoho_is_emailed', 'zoho_reminders_sent', 'zoho_last_reminder_sent_date', 'zoho_next_reminder_date_formatted', 'zoho_billing_address', 'zoho_shipping_address', 'zoho_notes', 'zoho_terms', 'zoho_custom_fields', 'zoho_custom_field_hash', 'zoho_template_id', 'zoho_template_name', 'zoho_template_type', 'zoho_page_width', 'zoho_page_height', 'zoho_orientation', 'zoho_created_time', 'zoho_last_modified_time', 'zoho_created_by_id', 'zoho_attachment_name', 'zoho_can_send_in_mail', 'zoho_salesperson_id', 'zoho_salesperson_name', 'zoho_is_autobill_enabled', 'zoho_invoice_url', 'created_date', 'modified_date'], 'safe'],
            [['zoho_payment_discount', 'zoho_discount', 'zoho_shipping_charge', 'zoho_sub_total', 'zoho_tax_total', 'zoho_total', 'zoho_balance'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Invoice::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => PAGE_SIZE),
            'sort'=> ['defaultOrder' => ['invoice_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['!=', 'status', 2]);
        $query->andFilterWhere(['!=', 'user_id', 0]);
        
        
        if (!empty($this->zoho_date)) {
            $query->andFilterWhere(['like','zoho_date',date('Y-m-d', strtotime($this->zoho_date))]);
            $this->zoho_date = $this->zoho_date ;
        }
        
                $query->andFilterWhere([
            'invoice_id' => $this->invoice_id,
            'zoho_due_date' => $this->zoho_due_date,
            'zoho_payment_discount' => $this->zoho_payment_discount,
            'zoho_discount' => $this->zoho_discount,
            'zoho_shipping_charge' => $this->zoho_shipping_charge,
            'zoho_sub_total' => $this->zoho_sub_total,
            'zoho_tax_total' => $this->zoho_tax_total,
            'zoho_total' => $this->zoho_total,
            'zoho_balance' => $this->zoho_balance,
            'zoho_price_precision' => $this->zoho_price_precision,
            'question_id' => $this->question_id,
            'transaction_id' => $this->transaction_id,
            'tutor_id' => $this->tutor_id,
            'student_id' => $this->student_id,
            'created_by' => $this->created_by,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'status' => $this->status,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'zoho_invoice_id', $this->zoho_invoice_id])
            ->andFilterWhere(['like', 'zoho_invoice_number', $this->zoho_invoice_number])
            ->andFilterWhere(['like', 'zoho_salesorder_id', $this->zoho_salesorder_id])
            ->andFilterWhere(['like', 'zoho_ach_payment_initiated', $this->zoho_ach_payment_initiated])
            ->andFilterWhere(['like', 'zoho_zcrm_potential_id', $this->zoho_zcrm_potential_id])
            ->andFilterWhere(['like', 'zoho_zcrm_potential_name', $this->zoho_zcrm_potential_name])
            ->andFilterWhere(['like', 'zoho_status', $this->zoho_status])
            ->andFilterWhere(['like', 'zoho_payment_terms', $this->zoho_payment_terms])
            ->andFilterWhere(['like', 'zoho_payment_terms_label', $this->zoho_payment_terms_label])
            ->andFilterWhere(['like', 'zoho_payment_expected_date', $this->zoho_payment_expected_date])
            ->andFilterWhere(['like', 'zoho_stop_reminder_until_payment_expected_date', $this->zoho_stop_reminder_until_payment_expected_date])
            ->andFilterWhere(['like', 'zoho_last_payment_date', $this->zoho_last_payment_date])
            ->andFilterWhere(['like', 'zoho_reference_number', $this->zoho_reference_number])
            ->andFilterWhere(['like', 'zoho_customer_id', $this->zoho_customer_id])
            ->andFilterWhere(['like', 'zoho_estimate_id', $this->zoho_estimate_id])
            ->andFilterWhere(['like', 'zoho_is_client_review_settings_enabled', $this->zoho_is_client_review_settings_enabled])
            ->andFilterWhere(['like', 'zoho_customer_name', $this->zoho_customer_name])
            ->andFilterWhere(['like', 'zoho_unused_retainer_payments', $this->zoho_unused_retainer_payments])
            ->andFilterWhere(['like', 'zoho_contact_persons', $this->zoho_contact_persons])
            ->andFilterWhere(['like', 'zoho_currency_id', $this->zoho_currency_id])
            ->andFilterWhere(['like', 'zoho_currency_code', $this->zoho_currency_code])
            ->andFilterWhere(['like', 'zoho_currency_symbol', $this->zoho_currency_symbol])
            ->andFilterWhere(['like', 'zoho_exchange_rate', $this->zoho_exchange_rate])
            ->andFilterWhere(['like', 'zoho_discount_applied_on_amount', $this->zoho_discount_applied_on_amount])
            ->andFilterWhere(['like', 'zoho_is_discount_before_tax', $this->zoho_is_discount_before_tax])
            ->andFilterWhere(['like', 'zoho_discount_type', $this->zoho_discount_type])
            ->andFilterWhere(['like', 'zoho_recurring_invoice_id', $this->zoho_recurring_invoice_id])
            ->andFilterWhere(['like', 'zoho_documents', $this->zoho_documents])
            ->andFilterWhere(['like', 'zoho_is_viewed_by_client', $this->zoho_is_viewed_by_client])
            ->andFilterWhere(['like', 'zoho_client_viewed_time', $this->zoho_client_viewed_time])
            ->andFilterWhere(['like', 'zoho_is_inclusive_tax', $this->zoho_is_inclusive_tax])
            ->andFilterWhere(['like', 'zoho_schedule_time', $this->zoho_schedule_time])
            ->andFilterWhere(['like', 'zoho_line_items', $this->zoho_line_items])
            ->andFilterWhere(['like', 'zoho_contact_persons_details', $this->zoho_contact_persons_details])
            ->andFilterWhere(['like', 'zoho_adjustment', $this->zoho_adjustment])
            ->andFilterWhere(['like', 'zoho_adjustment_description', $this->zoho_adjustment_description])
            ->andFilterWhere(['like', 'zoho_late_fee', $this->zoho_late_fee])
            ->andFilterWhere(['like', 'zoho_taxes', $this->zoho_taxes])
            ->andFilterWhere(['like', 'zoho_payment_reminder_enabled', $this->zoho_payment_reminder_enabled])
            ->andFilterWhere(['like', 'zoho_payment_made', $this->zoho_payment_made])
            ->andFilterWhere(['like', 'zoho_credits_applied', $this->zoho_credits_applied])
            ->andFilterWhere(['like', 'zoho_tax_amount_withheld', $this->zoho_tax_amount_withheld])
            ->andFilterWhere(['like', 'zoho_write_off_amount', $this->zoho_write_off_amount])
            ->andFilterWhere(['like', 'zoho_allow_partial_payments', $this->zoho_allow_partial_payments])
            ->andFilterWhere(['like', 'zoho_payment_options', $this->zoho_payment_options])
            ->andFilterWhere(['like', 'zoho_is_emailed', $this->zoho_is_emailed])
            ->andFilterWhere(['like', 'zoho_reminders_sent', $this->zoho_reminders_sent])
            ->andFilterWhere(['like', 'zoho_last_reminder_sent_date', $this->zoho_last_reminder_sent_date])
            ->andFilterWhere(['like', 'zoho_next_reminder_date_formatted', $this->zoho_next_reminder_date_formatted])
            ->andFilterWhere(['like', 'zoho_billing_address', $this->zoho_billing_address])
            ->andFilterWhere(['like', 'zoho_shipping_address', $this->zoho_shipping_address])
            ->andFilterWhere(['like', 'zoho_notes', $this->zoho_notes])
            ->andFilterWhere(['like', 'zoho_terms', $this->zoho_terms])
            ->andFilterWhere(['like', 'zoho_custom_fields', $this->zoho_custom_fields])
            ->andFilterWhere(['like', 'zoho_custom_field_hash', $this->zoho_custom_field_hash])
            ->andFilterWhere(['like', 'zoho_template_id', $this->zoho_template_id])
            ->andFilterWhere(['like', 'zoho_template_name', $this->zoho_template_name])
            ->andFilterWhere(['like', 'zoho_template_type', $this->zoho_template_type])
            ->andFilterWhere(['like', 'zoho_page_width', $this->zoho_page_width])
            ->andFilterWhere(['like', 'zoho_page_height', $this->zoho_page_height])
            ->andFilterWhere(['like', 'zoho_orientation', $this->zoho_orientation])
            ->andFilterWhere(['like', 'zoho_created_time', $this->zoho_created_time])
            ->andFilterWhere(['like', 'zoho_last_modified_time', $this->zoho_last_modified_time])
            ->andFilterWhere(['like', 'zoho_created_by_id', $this->zoho_created_by_id])
            ->andFilterWhere(['like', 'zoho_attachment_name', $this->zoho_attachment_name])
            ->andFilterWhere(['like', 'zoho_can_send_in_mail', $this->zoho_can_send_in_mail])
            ->andFilterWhere(['like', 'zoho_salesperson_id', $this->zoho_salesperson_id])
            ->andFilterWhere(['like', 'zoho_salesperson_name', $this->zoho_salesperson_name])
            ->andFilterWhere(['like', 'zoho_is_autobill_enabled', $this->zoho_is_autobill_enabled])
            ->andFilterWhere(['like', 'zoho_invoice_url', $this->zoho_invoice_url]);

        return $dataProvider;
    }
}
