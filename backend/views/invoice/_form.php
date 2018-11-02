<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\time\TimePicker;
use dosamigos\tinymce\TinyMce;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model common\models\Invoice */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="invoice-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'zoho_invoice_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_invoice_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_salesorder_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_ach_payment_initiated')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_zcrm_potential_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_zcrm_potential_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_date')->textInput() ?>

    <?= $form->field($model, 'zoho_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_payment_terms')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_payment_terms_label')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_due_date')->textInput() ?>

    <?= $form->field($model, 'zoho_payment_expected_date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_payment_discount')->textInput() ?>

    <?= $form->field($model, 'zoho_stop_reminder_until_payment_expected_date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_last_payment_date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_reference_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_customer_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_estimate_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_is_client_review_settings_enabled')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_customer_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_unused_retainer_payments')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_contact_persons')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'zoho_currency_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_currency_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_currency_symbol')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_exchange_rate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_discount')->textInput() ?>

    <?= $form->field($model, 'zoho_discount_applied_on_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_is_discount_before_tax')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_discount_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_recurring_invoice_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_documents')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'zoho_is_viewed_by_client')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_client_viewed_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_is_inclusive_tax')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_schedule_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_line_items')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'zoho_contact_persons_details')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'zoho_shipping_charge')->textInput() ?>

    <?= $form->field($model, 'zoho_adjustment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_adjustment_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'zoho_late_fee')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'zoho_sub_total')->textInput() ?>

    <?= $form->field($model, 'zoho_tax_total')->textInput() ?>

    <?= $form->field($model, 'zoho_total')->textInput() ?>

    <?= $form->field($model, 'zoho_taxes')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'zoho_payment_reminder_enabled')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_payment_made')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_credits_applied')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_tax_amount_withheld')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_balance')->textInput() ?>

    <?= $form->field($model, 'zoho_write_off_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_allow_partial_payments')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_price_precision')->textInput() ?>

    <?= $form->field($model, 'zoho_payment_options')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'zoho_is_emailed')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_reminders_sent')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_last_reminder_sent_date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_next_reminder_date_formatted')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_billing_address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'zoho_shipping_address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'zoho_notes')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'zoho_terms')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_custom_fields')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'zoho_custom_field_hash')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'zoho_template_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_template_name')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'zoho_template_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_page_width')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_page_height')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_orientation')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_created_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_last_modified_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_created_by_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_attachment_name')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'zoho_can_send_in_mail')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_salesperson_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_salesperson_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_is_autobill_enabled')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zoho_invoice_url')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'question_id')->textInput() ?>

    <?= $form->field($model, 'transaction_id')->textInput() ?>

    <?= $form->field($model, 'tutor_id')->textInput() ?>

    <?= $form->field($model, 'student_id')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <div class="form-group" id="button_update_delete">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['invoice/index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
    