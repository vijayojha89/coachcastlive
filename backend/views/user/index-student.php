<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use kartik\export\ExportMenu; 



/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Student Management');
$this->params['breadcrumbs'][] = $this->title;
?>
<style>

    .csv_ul{
        float:right;
        margin-bottom: 20px;
    }
    ul li{
        list-style: none;
    }
    ul.csv_ul li a {
        background: #5bb520 none repeat scroll 0 0 !important;
        border: 1px solid #5bb520 !important;
        border-radius: 0 !important;
        color: #fff !important;
        font-size: 16px !important;
        list-style: outside none none;
        padding: 5px 15px !important;
    }
    ul.csv_ul li a i{color: #fff !important;}

</style> 
<?php Pjax::begin(); ?><div class="user-index">
<div class="panel panel-default">
		<div class="panel-body">
                  
 <?php
$gridColumns = [
             [
                        'attribute' => 'email',
                        'label'=>'Email Address',
                    ],
             [
                        'attribute' => 'first_name',
                        'label'=>'First Name',
                    ], 
             [
                        'attribute' => 'last_name',
                        'label'=>'Last Name',
                    ],[
                        'attribute' => 'mobile_no',
                        'label'=>'Mobile Number',
                    ],[
                        'attribute' => 'expertise_ids',
                        'label'=>'Subjects',
                        'value' => function ($data) {
                             $snl = new \common\components\StudentComponent();
                              return $snl->user_subjects($data['id']);
                         },
                    ],[
                        'attribute' => 'qualification_id',
                        'label'=>'Qualification',
                        'value' => function ($data) {
                             $q = \common\models\Qualification::find()->where(['qualification_id'=>$data['qualification_id']])->asArray()->one();
                              return $q['name'];
                         },
                    ],
];
//echo '<pre>';print_r($gridColumns);exit;
?>                   
                    
<!--    <h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
   <?php
echo $exp_menu = "<ul class='csv_ul'>" . ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $gridColumns,
    'filename' => 'students_' . date("Y-m-d"),
    'target' => ExportMenu::TARGET_SELF,
    'asDropdown' => false,
    'options' => ['title' => 'Students'],
    'clearBuffers'=>true,
    'exportConfig' => [
        ExportMenu::FORMAT_TEXT => false,
        ExportMenu::FORMAT_PDF => false,
        ExportMenu::FORMAT_EXCEL => false,
        ExportMenu::FORMAT_EXCEL_X => false,
        ExportMenu::FORMAT_HTML => false,
        ExportMenu::FORMAT_CSV => [
            'label' => " Export CSV",
            'alertMsg' => false,
            'options' => ['title' => 'Students'],
        ],
    ]
]) . "</ul><div style='clear:both;'></div>";
?> 
   
    <?= GridView::widget([
        'export'=>false,
        'responsive'=>true,
        'pjax'=>FALSE,
        'resizableColumns'=>true,        
        'showPageSummary' => false,
        'panel' => [            
            'heading' => 'View',            
            'before' => Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create-student'],
            ['class' => 'btn btn-success']) . ' ' .
                Html::a('<i class="glyphicon glyphicon-refresh"></i>', ['student'], ['class' => 'btn btn-info']),
            
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
              'label' => Yii::t('app', 'Total Referral'),
              'attribute' => 'total_referral_user',
              'value' => function ($data) {
                    
                   return $data['total_referral_user'];
              
               },
               'format' => 'raw',
               'filter' => true,
            ],
                       [
              'label' => Yii::t('app', 'Total Questions'),
              'attribute' => 'total_questions',
              'value' => function ($data) {
                    
                   return $data['total_questions'];
              
               },
               'format' => 'raw',
               'filter' => true,
            ],
        [
                        'label' => Yii::t('app', 'Date of joining'),
                        'attribute' => 'created_date',
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
                        'attribute' => 'payment_verified',
                        'format' => 'raw',
                        'value' => function ($searchModel) {
                            $checked = $disabled = '';
                            if($searchModel->payment_verified == 1){
                                $checked = 'checked';
                                $disabled = 'disabled';
                            }
                                    return '<input type="checkbox" onclick="verification('.$searchModel->id.',1)" 
                                            name="payment_verified" value=""  '.$checked.' '.$disabled.'>';
                                    },
                        'filter' =>FALSE,
                        'enableSorting'=>FALSE,

                    ],
                                           [
                        'attribute' => 'mobile_verified',
                        'format' => 'raw',
                        'value' => function ($searchModel) {
                            $checked = $disabled= '';
                            if($searchModel->mobile_verified == 1){
                                $checked = 'checked';
                                $disabled = 'disabled';
                            }
                                    return '<input type="checkbox" onclick="verification('.$searchModel->id.',2)" 
                                     name="mobile_verified" value=""  '.$checked.' '.$disabled.'>';
                                    },
                        'filter' =>FALSE,
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
            // 'role',
            // 'user_role',
            // 'user_s3_accesskey',
            // 'user_s3_secretkey',
            // 'user_s3_region',
            // 'user_s3_bucket',
            // 'user_last_login',
            // 'created_at',
            // 'updated_at',
[
            'value'=>function($model){
                if($model->social_type == 0){
		return Html::a(
                       Yii::t('app', 'Change Password'),
		['change-password-student', 'id'=>  common\components\GeneralComponent::encrypt($model->id)],
                   ['class'=>'btn btn-sm btn-default', 'data-pjax'=>0]);}
                else { return '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp---';}
			},
			'format'=>'raw',
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
                return Html::a('', 'student'.$string, [                                                  
                            'class'=>$icon,'onClick'=>'active_deactive(\''.$url.'\')',                                  
                ]);
            },
                            'update' => function ($url, $model) {
                    return Html::a('',['update-student', 'id'=>  common\components\GeneralComponent::encrypt($model->id)],
				      ['class'=>'glyphicon glyphicon-pencil', 'data-pjax'=>0]);
            }, 
                            'delete' => function ($url, $model) {
                    return Html::a('', ['delete-student', 'id'=>  common\components\GeneralComponent::encrypt($model->id)], ['class'=>'glyphicon glyphicon-trash',
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
      

function verification(id,type){ 
             
             var r = confirm("Are you sure?");
            if(r==true){
            $.ajax({
                   url: "'.YII::$app->homeUrl.'user/verify?id="+id+"&type="+type, 
                   success: function(result){
                   $("#w3").load(location.href+" #w3>*","");
           }});
           }
}
            ',
        \yii\web\VIEW::POS_HEAD); ?><?php Pjax::end(); ?>