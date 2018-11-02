<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\InvoiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoice-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'invoice_id') ?>

    <?= $form->field($model, 'zoho_invoice_id') ?>

    <?= $form->field($model, 'zoho_invoice_number') ?>

    <?= $form->field($model, 'zoho_salesorder_id') ?>

    <?= $form->field($model, 'zoho_ach_payment_initiated') ?>

    <?php // echo $form->field($model, 'zoho_zcrm_potential_id') ?>

    <?php // echo $form->field($model, 'zoho_zcrm_potential_name') ?>

    <?php // echo $form->field($model, 'zoho_date') ?>

    <?php // echo $form->field($model, 'zoho_status') ?>

    <?php // echo $form->field($model, 'zoho_payment_terms') ?>

    <?php // echo $form->field($model, 'zoho_payment_terms_label') ?>

    <?php // echo $form->field($model, 'zoho_due_date') ?>

    <?php // echo $form->field($model, 'zoho_payment_expected_date') ?>

    <?php // echo $form->field($model, 'zoho_payment_discount') ?>

    <?php // echo $form->field($model, 'zoho_stop_reminder_until_payment_expected_date') ?>

    <?php // echo $form->field($model, 'zoho_last_payment_date') ?>

    <?php // echo $form->field($model, 'zoho_reference_number') ?>

    <?php // echo $form->field($model, 'zoho_customer_id') ?>

    <?php // echo $form->field($model, 'zoho_estimate_id') ?>

    <?php // echo $form->field($model, 'zoho_is_client_review_settings_enabled') ?>

    <?php // echo $form->field($model, 'zoho_customer_name') ?>

    <?php // echo $form->field($model, 'zoho_unused_retainer_payments') ?>

    <?php // echo $form->field($model, 'zoho_contact_persons') ?>

    <?php // echo $form->field($model, 'zoho_currency_id') ?>

    <?php // echo $form->field($model, 'zoho_currency_code') ?>

    <?php // echo $form->field($model, 'zoho_currency_symbol') ?>

    <?php // echo $form->field($model, 'zoho_exchange_rate') ?>

    <?php // echo $form->field($model, 'zoho_discount') ?>

    <?php // echo $form->field($model, 'zoho_discount_applied_on_amount') ?>

    <?php // echo $form->field($model, 'zoho_is_discount_before_tax') ?>

    <?php // echo $form->field($model, 'zoho_discount_type') ?>

    <?php // echo $form->field($model, 'zoho_recurring_invoice_id') ?>

    <?php // echo $form->field($model, 'zoho_documents') ?>

    <?php // echo $form->field($model, 'zoho_is_viewed_by_client') ?>

    <?php // echo $form->field($model, 'zoho_client_viewed_time') ?>

    <?php // echo $form->field($model, 'zoho_is_inclusive_tax') ?>

    <?php // echo $form->field($model, 'zoho_schedule_time') ?>

    <?php // echo $form->field($model, 'zoho_line_items') ?>

    <?php // echo $form->field($model, 'zoho_contact_persons_details') ?>

    <?php // echo $form->field($model, 'zoho_shipping_charge') ?>

    <?php // echo $form->field($model, 'zoho_adjustment') ?>

    <?php // echo $form->field($model, 'zoho_adjustment_description') ?>

    <?php // echo $form->field($model, 'zoho_late_fee') ?>

    <?php // echo $form->field($model, 'zoho_sub_total') ?>

    <?php // echo $form->field($model, 'zoho_tax_total') ?>

    <?php // echo $form->field($model, 'zoho_total') ?>

    <?php // echo $form->field($model, 'zoho_taxes') ?>

    <?php // echo $form->field($model, 'zoho_payment_reminder_enabled') ?>

    <?php // echo $form->field($model, 'zoho_payment_made') ?>

    <?php // echo $form->field($model, 'zoho_credits_applied') ?>

    <?php // echo $form->field($model, 'zoho_tax_amount_withheld') ?>

    <?php // echo $form->field($model, 'zoho_balance') ?>

    <?php // echo $form->field($model, 'zoho_write_off_amount') ?>

    <?php // echo $form->field($model, 'zoho_allow_partial_payments') ?>

    <?php // echo $form->field($model, 'zoho_price_precision') ?>

    <?php // echo $form->field($model, 'zoho_payment_options') ?>

    <?php // echo $form->field($model, 'zoho_is_emailed') ?>

    <?php // echo $form->field($model, 'zoho_reminders_sent') ?>

    <?php // echo $form->field($model, 'zoho_last_reminder_sent_date') ?>

    <?php // echo $form->field($model, 'zoho_next_reminder_date_formatted') ?>

    <?php // echo $form->field($model, 'zoho_billing_address') ?>

    <?php // echo $form->field($model, 'zoho_shipping_address') ?>

    <?php // echo $form->field($model, 'zoho_notes') ?>

    <?php // echo $form->field($model, 'zoho_terms') ?>

    <?php // echo $form->field($model, 'zoho_custom_fields') ?>

    <?php // echo $form->field($model, 'zoho_custom_field_hash') ?>

    <?php // echo $form->field($model, 'zoho_template_id') ?>

    <?php // echo $form->field($model, 'zoho_template_name') ?>

    <?php // echo $form->field($model, 'zoho_template_type') ?>

    <?php // echo $form->field($model, 'zoho_page_width') ?>

    <?php // echo $form->field($model, 'zoho_page_height') ?>

    <?php // echo $form->field($model, 'zoho_orientation') ?>

    <?php // echo $form->field($model, 'zoho_created_time') ?>

    <?php // echo $form->field($model, 'zoho_last_modified_time') ?>

    <?php // echo $form->field($model, 'zoho_created_by_id') ?>

    <?php // echo $form->field($model, 'zoho_attachment_name') ?>

    <?php // echo $form->field($model, 'zoho_can_send_in_mail') ?>

    <?php // echo $form->field($model, 'zoho_salesperson_id') ?>

    <?php // echo $form->field($model, 'zoho_salesperson_name') ?>

    <?php // echo $form->field($model, 'zoho_is_autobill_enabled') ?>

    <?php // echo $form->field($model, 'zoho_invoice_url') ?>

    <?php // echo $form->field($model, 'question_id') ?>

    <?php // echo $form->field($model, 'transaction_id') ?>

    <?php // echo $form->field($model, 'tutor_id') ?>

    <?php // echo $form->field($model, 'student_id') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_date') ?>

    <?php // echo $form->field($model, 'modified_date') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
