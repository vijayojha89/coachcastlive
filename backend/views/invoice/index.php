<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(); 

$user_value_by = '';
if(isset($_REQUEST['Invoice']['user_id'])){
$user_value_by = $_REQUEST['Invoice']['user_id'];}

?>
<div class="invoice-index">
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
            'before' => Html::a('<i class="glyphicon glyphicon-refresh"></i>', ['index'], ['class' => 'btn btn-info']),
            
        ],
        'toolbar' => [     
    '{toggleData}'
],
    
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                    'class'=>'kartik\grid\ExpandRowColumn',
                    'width'=>'50px',
                    'value'=>function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detail'=>function ($model, $key, $index, $column) {
                        return Yii::$app->controller->renderPartial('_expand-row-details', ['model'=>$model]);
                    },
                    'headerOptions'=>['class'=>'kartik-sheet-style'] ,
                    'expandOneOnly'=>true,
                                'enableRowClick' => true,
            ],
               
            [
                        'attribute' => 'user_id',
                        'enableSorting'=>FALSE,
                        'value' => function ($data) {
                            return implode(' ', ArrayHelper::map(common\models\User::find()
                                               ->select(new \yii\db\Expression("CONCAT(`first_name`, '  ', `last_name`) as name"))
                                               ->where(['id' => $data['user_id']])->asArray()->all(), 'id', 'name'));
                        },
                        'filter' => '<input type="text" class="form-control" name="Invoice[user_id]" value="'.$user_value_by.'">',
            ],   
            
           
[
                'attribute' => 'zoho_invoice_id',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'zoho_invoice_number',
                'enableSorting'=>FALSE,
            ],

[
                        
                        'enableSorting'=>FALSE,
                        'attribute' => 'zoho_date',
                        'format' => ['date', 'php:m/d/Y'],
                        'filterType' => GridView::FILTER_DATE,
                        'filterWidgetOptions' => [
                            'pluginOptions' => [
                                'format' => 'mm/dd/yyyy',
                                'weekStart' => '1',
                                'autoWidget' => true,
                                'autoclose' => true,
                                'todayBtn' => FALSE,
                            ],
                            'options' => ['placeholder' => ''],
                        ],
                    ],  
[
                'attribute' => 'zoho_status',
                'enableSorting'=>FALSE,
            ],


[
                        'attribute' => 'zoho_total',
                        'format' => 'raw',
                        'value' => function ($data) { 
                                        
                                        return common\components\GeneralComponent::front_priceformat($data['zoho_total']);
                    
                                    },
                        'filter' => '',
                        'enableSorting'=>FALSE,

                    ], 
                                            
              [
                        'label'=>'',
                        'attribute' => '',
                        'format' => 'raw',
                        'value' => function ($data) { 
                                        
                                        return '<a href="'.$data['zoho_invoice_url'].'" target="_blank" class="btn btn-warning">View Invoice Url</a>';
                    
                                    },
                        'filter' => '',
                        'enableSorting'=>FALSE,

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