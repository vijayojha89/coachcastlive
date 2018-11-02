<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Transaction */

$this->title = $model->transaction_id;
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->transaction_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->transaction_id], [
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
            'transaction_id',
            'studypad_txn_id',
            'user_id',
            'question_id',
            'stripe_charge_id',
            'stripe_card_id',
            'amount',
            'amount_refunded',
            'balance_transaction',
            'captured',
            'stripe_customer_id',
            'failure_code',
            'failure_message',
            'paid',
            'created_by',
            'created_date',
            'modified_date',
            'status',
        ],
    ]) ?>

</div>

