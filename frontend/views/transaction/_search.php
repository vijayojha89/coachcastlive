<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\TransactionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transaction-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'transaction_id') ?>

    <?= $form->field($model, 'studypad_txn_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'question_id') ?>

    <?= $form->field($model, 'stripe_charge_id') ?>

    <?php // echo $form->field($model, 'stripe_card_id') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'amount_refunded') ?>

    <?php // echo $form->field($model, 'balance_transaction') ?>

    <?php // echo $form->field($model, 'captured') ?>

    <?php // echo $form->field($model, 'stripe_customer_id') ?>

    <?php // echo $form->field($model, 'failure_code') ?>

    <?php // echo $form->field($model, 'failure_message') ?>

    <?php // echo $form->field($model, 'paid') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_date') ?>

    <?php // echo $form->field($model, 'modified_date') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
