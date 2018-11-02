<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(); ?><div class="transaction-index">
<div class="panel panel-default">
		<div class="panel-body">
                  
                    
                    
<!--    <h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   
    <?= GridView::widget([
        'export'=>false,
        'responsive'=>true,
        'pjax'=>true,
        'resizableColumns'=>true,        
        'showPageSummary' => false,
        'panel' => [            
            'heading' => 'View',            
            'before' => Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
            ['class' => 'btn btn-success']) . ' ' .                   Html::a('<i class="glyphicon glyphicon-refresh"></i>', ['index'], ['class' => 'btn btn-info']),
            
        ],
        'toolbar' => [     
    '{toggleData}'
],
    
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

[
                'attribute' => 'studypad_txn_id',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'user_id',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'question_id',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'stripe_charge_id',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'stripe_card_id',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'amount',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'amount_refunded',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'balance_transaction',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'captured',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'stripe_customer_id',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'failure_code',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'failure_message',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'paid',
                'enableSorting'=>FALSE,
            ],

            ['class' => 'kartik\grid\ActionColumn',
             'template' => '{is-active} {update} {delete}',
                'buttons' => [
                            'is-active' => function ($url, $model) {
                
                if($model->status==0){
                        $icon = 'glyphicon glyphicon-eye-close text-danger';
                }else if($model->status==1){
                        $icon = 'glyphicon glyphicon-eye-open text-success';
                }else{
                        $icon = 'glyphicon glyphicon-eye-open text-success';
                }
                if(!empty($_SERVER['QUERY_STRING'])){
                                    $string = '?'.$_SERVER['QUERY_STRING'];
                                }else{
                                    $string = '';
                                }
                return Html::a('', 'index'.$string, [                                                  
                            'class'=>$icon,'onClick'=>'active_deactive(\''.$url.'\')',                                  
                ]);
            },
                            'update' => function ($url, $model) {
                    return Html::a('',['update', 'id'=>  common\components\GeneralComponent::encrypt($model->transaction_id)],
				      ['class'=>'glyphicon glyphicon-pencil', 'data-pjax'=>0]);
            }, 
                            'delete' => function ($url, $model) {
                    return Html::a('', ['delete', 'id'=>  common\components\GeneralComponent::encrypt($model->transaction_id)], ['class'=>'glyphicon glyphicon-trash',
                        'data-confirm'=>"Are you sure to delete this item?",
                        'title'=>"delete",'data-pjax'=>"false","data-pjax-container"=>"w1-pjax"]);
            }
                        ]   
            
            ],
        ],
    ]); ?>
                </div>
                </div>
</div>

<?php $this->registerJs(
                
         '
             function active_deactive(url){
             
    $.ajax({
            url: url, 
            success: function(result){
            //alert(result);
    }});

  }
            
            ',
        \yii\web\VIEW::POS_HEAD); ?><?php Pjax::end(); ?>