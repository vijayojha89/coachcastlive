<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $searchModel common\models\NeighbourhoodWatchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'Expertise Requests');
$this->params['breadcrumbs'][] = $this->title;
if(isset($_REQUEST['Subject']['created_by'])){
$user_value_by = $_REQUEST['Subject']['created_by'];}
else{$user_value_by = '';}
?>
<?php Pjax::begin(); ?>
<div class="streets-index">
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
            'before' => //Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
            //['class' => 'btn btn-success']) . ' ' .                  
            Html::a('<i class="glyphicon glyphicon-refresh"></i>', ['requests'], ['class' => 'btn btn-info']),
            
        ],
        'toolbar' => [     
    '{toggleData}'
],
    
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
[
                'attribute' => 'name',
                'enableSorting'=>FALSE,
                'contentOptions'=>['style'=>'text-align: left;']
            ],
[
                        'attribute' => 'created_by',
                        'enableSorting'=>FALSE,
                        'label' => 'Requested By',
                        'value' => function ($data) {
                            return implode(' ', ArrayHelper::map(common\models\User::find()
                                               ->select(new \yii\db\Expression("CONCAT(`first_name`, '  ', `last_name`) as name"))
                                               ->where(['id' => $data['created_by']])->asArray()->all(), 'id', 'name'));
                        },
                'filter' => '<input type="text" class="form-control" name="Subject[created_by]" value="'.$user_value_by.'">',
                    ], 
             
              [
                        'attribute' => 'request_status',
                        'format' => 'raw',
                        'value' => function ($searchModel) { 
                                    $disabled = FALSE;
                                    if($searchModel->request_status == 2 || $searchModel->request_status == 1){$disabled = TRUE;}
                                    return Html::activeDropDownList($searchModel, 'request_status',
                                    ['0'=>'Pending','1' => 'Accepted','2' => 'Rejected'], 
                                    ['class' => 'form-control', 'disabled' => $disabled, 'data-pjax' => true ,
                                     'onchange'=>'request_status('.$searchModel->subject_id.',this.value)']);
                                    },
                        'filter' => Html::activeDropDownList($searchModel, 'request_status',
                                    ['0'=>'Pending','2' => 'Rejected'], ['class' => 'form-control', 'prompt' => 'All']),
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
        \yii\web\VIEW::POS_HEAD); ?>

<?php $this->registerJs(
                
         '
             function request_status(id,status){ 
             
             var r = confirm("Are you sure?");
            if(r==true){
            $.ajax({
                   url: "'.YII::$app->homeUrl.'subject/request-status?id="+id+"&status="+status, 
                   success: function(result){
                   $("#w1-container").load(location.href+" #w1-container>*","");
           }});
}
  }
            
            ',
        \yii\web\VIEW::POS_HEAD); ?><?php Pjax::end(); ?>