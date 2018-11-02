<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use kartik\export\ExportMenu; 


/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Sub-Admin Management');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(); ?><div class="user-index">
<div class="panel panel-default">
		<div class="panel-body">
                  
                    
                    
<!--    <h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
   
   
    <?= GridView::widget([
        'export'=>false,
        'responsive'=>true,
        'pjax'=>FALSE,
        'resizableColumns'=>true,        
        'showPageSummary' => false,
        'panel' => [            
            'heading' => 'View',            
            'before' => Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create-sub-admin'],
            ['class' => 'btn btn-success']) . ' ' .
                Html::a('<i class="glyphicon glyphicon-refresh"></i>', ['sub-admin'], ['class' => 'btn btn-info']),
            
        ],
        'toolbar' => [     
    '{toggleData}'
],
    
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
 [
                        'attribute' => 'first_name',
                        'enableSorting'=>FALSE,
                    ], 
        [
                        'attribute' => 'last_name',
                        'enableSorting'=>FALSE,
                    ],
             [
                        'attribute' => 'email',
                        'enableSorting'=>FALSE,
                    ],
[
              'label' => Yii::t('app', 'Profile Photo'),
              'attribute' => 'ad_image',
              'value' => function ($data) {
                    
                   $gnl = new \common\components\GeneralComponent();
                    return '<img width="80" height="80" src="'.$gnl->image_not_found_hb( $data['profile_photo'],'profile_photo') .'"/>';
              
               },
               'format' => 'raw',
               'filter' => false,
            ],  
        [
            'value'=>function($model){
		return Html::a(
                       Yii::t('app', 'Change Password'),
		['change-password-sub-admin', 'id'=>  common\components\GeneralComponent::encrypt($model->id)],
		['class'=>'btn btn-sm btn-default', 'data-pjax'=>0]);
			},
			'format'=>'raw',
	],           
            // 'role',
            // 'user_role',
            // 'user_s3_accesskey',
            // 'user_s3_secretkey',
            // 'user_s3_region',
            // 'user_s3_bucket',
            // 'user_last_login',
            // 'created_at',
            // 'updated_at',

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
                return Html::a('', 'sub-admin'.$string, [                                                  
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