<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $searchModel common\models\DynamicMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Dynamic Menus');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(); ?><div class="dynamic-menu-index">
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
            ['class' => 'btn btn-success']) . ' ' .  
            Html::a('Add Data in Dropdown', ['add'],
            ['class' => 'btn btn-success']) . ' ' .
            Html::a(Yii::t('app', ' Sort'), Yii::$app->getUrlManager()->getBaseUrl().'/dynamic-menu/index-sortable' ,  [
                                            'class' => 'btn btn-primary',   
                                ]). ' ' .                Html::a('<i class="glyphicon glyphicon-refresh"></i>', ['index'], ['class' => 'btn btn-info']),
            
        ],
        'toolbar' => [     
    '{toggleData}'
],
    
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

//            'id',
            [
                        'attribute' => 'controller_id',
                        'value' => function ($data) {
                            return implode(' ', ArrayHelper::map(common\models\AuthItems::findAll(array('auth_items_id' => $data['controller_id'],'parent_auth_item_id'=>0)), 'auth_items_id', 'auth_items_name'));
                        },
                        'filter' => Html::activeDropDownList($searchModel, 'controller_id',
                                ArrayHelper::map(common\models\AuthItems::find()->where(['parent_auth_item_id'=>0])->asArray()->all(), 'auth_items_id', 'auth_items_name'), ['class' => 'form-control', 'prompt' => 'All']),
                        'format' => 'raw',
                        'enableSorting'=>FALSE,

                    ],    
            'label',
            'url:url',
            'index_array',
            // 'sort',
             'icon',
             'key',
            // 'description',

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
                if($model->type==0){
                        $action = 'update-dropdown';
                }else{
                        $action = 'update';
                }
                    return Html::a('',[$action, 'id'=>  common\components\GeneralComponent::encrypt($model->id)],
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