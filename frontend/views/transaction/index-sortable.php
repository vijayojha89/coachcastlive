<?php
use yii\widgets\Pjax;
use richardfan\sortable\SortableGridView;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions';
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(); ?><div class="transaction-index">
<div class="panel panel-default">
		<div class="panel-body">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php $dataProvider->pagination  = false;
        echo  SortableGridView::widget([ 
    'dataProvider' => $dataProvider,
    'sortUrl' => Url::to(['sortItem']),
    'sortingPromptText' => 'Sorting...',
    'failText' => 'Fail to sort',
        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'transaction_id',
            'studypad_txn_id',
            'user_id',
            'question_id',
            'stripe_charge_id',
            // 'stripe_card_id',
            // 'amount',
            // 'amount_refunded',
            // 'balance_transaction',
            // 'captured',
            // 'stripe_customer_id',
            // 'failure_code',
            // 'failure_message',
            // 'paid',

        ],
    ]); ?>
                </div>
                </div>
</div>

<style>
    .summary{
        display: none;
    }
</style>
