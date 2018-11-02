<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Invoice */

$this->title = $model->invoice_id;
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->invoice_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->invoice_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'invoice_id',
            'zoho_invoice_id',
            'zoho_invoice_number',
            'zoho_salesorder_id',
            'zoho_ach_payment_initiated',
            'zoho_zcrm_potential_id',
            'zoho_zcrm_potential_name',
            'zoho_date',
            'zoho_status',
            'zoho_payment_terms',
            'zoho_payment_terms_label',
            'zoho_due_date',
            'zoho_payment_expected_date',
            'zoho_payment_discount',
            'zoho_stop_reminder_until_payment_expected_date',
            'zoho_last_payment_date',
            'zoho_reference_number',
            'zoho_customer_id',
            'zoho_estimate_id',
            'zoho_is_client_review_settings_enabled',
            'zoho_customer_name',
            'zoho_unused_retainer_payments',
            'zoho_contact_persons:ntext',
            'zoho_currency_id',
            'zoho_currency_code',
            'zoho_currency_symbol',
            'zoho_exchange_rate',
            'zoho_discount',
            'zoho_discount_applied_on_amount',
            'zoho_is_discount_before_tax',
            'zoho_discount_type',
            'zoho_recurring_invoice_id',
            'zoho_documents:ntext',
            'zoho_is_viewed_by_client',
            'zoho_client_viewed_time',
            'zoho_is_inclusive_tax',
            'zoho_schedule_time',
            'zoho_line_items:ntext',
            'zoho_contact_persons_details:ntext',
            'zoho_shipping_charge',
            'zoho_adjustment',
            'zoho_adjustment_description:ntext',
            'zoho_late_fee:ntext',
            'zoho_sub_total',
            'zoho_tax_total',
            'zoho_total',
            'zoho_taxes:ntext',
            'zoho_payment_reminder_enabled',
            'zoho_payment_made',
            'zoho_credits_applied',
            'zoho_tax_amount_withheld',
            'zoho_balance',
            'zoho_write_off_amount',
            'zoho_allow_partial_payments',
            'zoho_price_precision',
            'zoho_payment_options:ntext',
            'zoho_is_emailed:email',
            'zoho_reminders_sent',
            'zoho_last_reminder_sent_date',
            'zoho_next_reminder_date_formatted',
            'zoho_billing_address:ntext',
            'zoho_shipping_address:ntext',
            'zoho_notes:ntext',
            'zoho_terms',
            'zoho_custom_fields:ntext',
            'zoho_custom_field_hash:ntext',
            'zoho_template_id',
            'zoho_template_name:ntext',
            'zoho_template_type',
            'zoho_page_width',
            'zoho_page_height',
            'zoho_orientation',
            'zoho_created_time',
            'zoho_last_modified_time',
            'zoho_created_by_id',
            'zoho_attachment_name:ntext',
            'zoho_can_send_in_mail',
            'zoho_salesperson_id',
            'zoho_salesperson_name',
            'zoho_is_autobill_enabled',
            'zoho_invoice_url:ntext',
            'question_id',
            'transaction_id',
            'tutor_id',
            'student_id',
            'created_by',
            'created_date',
            'modified_date',
            'status',
            'user_id',
        ],
    ]) ?>

</div>

