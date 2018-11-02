<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\select2\Select2;



/* @var $this yii\web\View */
/* @var $searchModel app\models\SettingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(); ?><div class="setting-index">
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
            'heading' => 'View setting',            
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

            'setting_id',
            'setting_google',
            // 'setting_facebook',
            // 'setting_linkedin',
            // 'setting_yahoo',
[
              'label' => Yii::t('app', 'setting_logo_image'),
              'attribute' => 'ad_image',
              'value' => function ($data) {
                    
                   $gnl = new \common\components\GeneralComponent();
                    return '<img width="80" height="80" src="'.$gnl->image_not_found('setting_logo_image', $data['setting_logo_image']).'"/>';
              
               },
               'format' => 'raw',
               'filter' => false,
            ],[
              'label' => Yii::t('app', 'setting_favicon_image'),
              'attribute' => 'ad_image',
              'value' => function ($data) {
                    
                   $gnl = new \common\components\GeneralComponent();
                    return '<img width="80" height="80" src="'.$gnl->image_not_found('setting_favicon_image', $data['setting_favicon_image']).'"/>';
              
               },
               'format' => 'raw',
               'filter' => false,
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