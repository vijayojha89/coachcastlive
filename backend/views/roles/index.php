<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\select2\Select2;



/* @var $this yii\web\View */
/* @var $searchModel common\models\RolesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Roles';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(); ?><div class="roles-index">
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
            'heading' => 'View roles',            
            'before' => Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
            ['class' => 'btn btn-success']) . ' ' .
                Html::a('<i class="glyphicon glyphicon-refresh"></i>', ['index'], ['class' => 'btn btn-info']),
            
        ],
        'toolbar' => [     
    '{toggleData}'
],
    
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

           // 'id',
            [
              'attribute' => 'role_name',
              'enableSorting'=>FALSE,       
            ], 
            [
              'attribute' => 'role_description',
              'enableSorting'=>FALSE,       
            ], 
            [
              'label' => Yii::t('app', 'View'),
                'value' => function ($data) {
                    return Html::a(Yii::t('app', ' View'), YII::$app->homeUrl.'roles/role-allocation?id=' .common\components\GeneralComponent::encrypt($data['id']),  [
                                            'class' => 'btn btn-primary',   
                                ]);
               },
               'format' => 'raw',
                                            
            ], 
            ['class' => 'kartik\grid\ActionColumn',
             'template' => '{is-active} {update} {delete}',
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
                            'update' => function ($url, $model) {
                    return Html::a('',['update', 'id'=>  common\components\GeneralComponent::encrypt($model->id)],
				      ['class'=>'glyphicon glyphicon-pencil', 'data-pjax'=>0]);
            }, 
                            'delete' => function ($url, $model) {
                    return Html::a('', ['delete', 'id'=>  common\components\GeneralComponent::encrypt($model->id)], ['class'=>'glyphicon glyphicon-trash',
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