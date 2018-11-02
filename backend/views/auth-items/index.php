<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\select2\Select2;



/* @var $this yii\web\View */
/* @var $searchModel common\models\AuthItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Actions');
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['roles/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Pjax::begin(); ?><div class="auth-items-index">
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
            'before' => 
//            Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
//            ['class' => 'btn btn-success']) . ' ' .
                Html::a('<i class="glyphicon glyphicon-refresh"></i>', ['index'], ['class' => 'btn btn-info']),
            
        ],
        'toolbar' => [     
    '{toggleData}'
],
    
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

//            'auth_items_id',
            'auth_items_name',
            'auth_items_type',
//            'auth_items_action',

            ['class' => 'kartik\grid\ActionColumn',
             'template' => '{delete}',
                'buttons' => [
                            'is-active' => function ($url, $model) {
                
                if($model->status==1){
                        $icon = 'glyphicon glyphicon-eye-close text-danger';
                }else if($model->status==0){
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