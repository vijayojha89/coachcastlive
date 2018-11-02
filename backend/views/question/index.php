<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $searchModel common\models\QuestionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Questions');
$this->params['breadcrumbs'][] = $this->title;

if(isset($_REQUEST['Question']['created_by'])){
$user_value_by = $_REQUEST['Question']['created_by'];}
else{$user_value_by = '';}
?>
<?php Pjax::begin(); ?><div class="question-index">
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
            'before' =>  Html::a('<i class="glyphicon glyphicon-refresh"></i>', ['index'], ['class' => 'btn btn-info']),
            
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
                'attribute' => 'title',
                'value' => function ($data) {
                            $stringCut = substr($data['title'], 0, 30);
                            return substr($stringCut, 0, strrpos($stringCut, ' ')).'... ';
                        },
                'enableSorting'=>FALSE,
            ],
[
                        'attribute' => 'created_by',
                        'enableSorting'=>FALSE,
                        'label' => 'Created By',
                        'value' => function ($data) {
                            return implode(' ', ArrayHelper::map(common\models\User::find()
                                               ->select(new \yii\db\Expression("CONCAT(`first_name`, '  ', `last_name`) as name"))
                                               ->where(['id' => $data['created_by']])->asArray()->all(), 'id', 'name'));
                        },
                'filter' => '<input type="text" class="form-control" name="Question[created_by]" value="'.$user_value_by.'">',
                    ],            
/*[
                'attribute' => 'description',
                'enableSorting'=>FALSE,
            ],
 * 
 */
[
                'attribute' => 'time_limit',
                'enableSorting'=>FALSE,
            ],
[
                        'attribute' => 'is_priority_set',
                        'format' => 'raw',
                        'value' => function ($searchModel) { 
                                    if($searchModel->is_priority_set == 0){return 'No';}
                                    if($searchModel->is_priority_set == 1){return 'Yes';}
                                    },
                        'filter' => Html::activeDropDownList($searchModel, 'is_priority_set',
                                    ['0'=>'No','1' => 'Yes'], ['class' => 'form-control', 'prompt' => 'All']),
                        'enableSorting'=>FALSE,

                    ], 
[
                        'attribute' => 'qualification_id',
                        'value' => function ($data) {
                            return implode(' ', ArrayHelper::map(common\models\Qualification::findAll(array('qualification_id' => $data['qualification_id'])), 'qualification_id', 'name'));
                        },
                        'format' => 'raw',
                        'filter' => Html::activeDropDownList($searchModel, 'qualification_id',
                                ArrayHelper::map(common\models\Qualification::find()->asArray()->all(), 'qualification_id', 'name'), ['class' => 'form-control', 'prompt' => 'All']),
                        'enableSorting'=>FALSE,

                    ],                                            
[
                        'attribute' => 'subject_id',
                        'value' => function ($data) {
                            return implode(' ', ArrayHelper::map(\common\models\Subject::findAll(array('subject_id' => $data['subject_id'])), 'subject_id', 'name'));
                        },
                        'format' => 'raw',
                        'filter' => Html::activeDropDownList($searchModel, 'subject_id',
                                ArrayHelper::map(\common\models\Subject::find()->asArray()->all(), 'subject_id', 'name'), ['class' => 'form-control', 'prompt' => 'All']),
                        'enableSorting'=>FALSE,

                    ], 
[
                        'attribute' => 'price_type',
                        'format' => 'raw',
                        'value' => function ($searchModel) { 
                                    if($searchModel->price_type == 2){return 'Budget';}
                                    if($searchModel->price_type == 1){return 'Fixed';}
                                    },
                        'filter' => Html::activeDropDownList($searchModel, 'price_type',
                                    ['1'=>'Fixed','2' => 'Budget'], ['class' => 'form-control', 'prompt' => 'All']),
                        'enableSorting'=>FALSE,

                    ],                                             

/*[
                'attribute' => 'price',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'discount_price',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'min_budget',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'max_budget',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'confirm_bid',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'bid_status',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'confirm_select_tutor',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'answer_id',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'mark_completed_student',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'mark_completed_tutor',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'tutor_marked_time',
                'enableSorting'=>FALSE,
            ],
[
                'attribute' => 'student_marked_time',
                'enableSorting'=>FALSE,
            ],
 * 
 */
                [
                        'label' => Yii::t('app', 'Asked Date'),
                        'enableSorting'=>FALSE,
                        'attribute' => 'asked_date',
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

/*[
                'attribute' => 'completed_date',
                'enableSorting'=>FALSE,
            ],
 * 
 */
[
                        'attribute' => 'question_status',
                        'format' => 'raw',
                        'value' => function ($searchModel) { 
                                    if($searchModel->question_status == 4 || $searchModel->question_status == 5 ||$searchModel->question_status == 6 || $searchModel->question_status == 7)
                                      {return 'Cancelled';}
                                    if($searchModel->question_status == 3){return 'Expired';}
                                    if($searchModel->question_status == 2){return 'Completed';}
                                    if($searchModel->question_status == 1){return 'Active';}
                                    },
                        'filter' => Html::activeDropDownList($searchModel, 'question_status',
                                    ['1'=>'Active','2' => 'Completed','3'=>'Expired','4'=>'Cancelled'], ['class' => 'form-control', 'prompt' => 'All']),
                        'enableSorting'=>FALSE,

                    ],                                             


            ['class' => 'kartik\grid\ActionColumn',
             'template' => '{delete}',
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
                    return Html::a('',['update', 'id'=>  common\components\GeneralComponent::encrypt($model->question_id)],
				      ['class'=>'glyphicon glyphicon-pencil', 'data-pjax'=>0]);
            }, 
                            'delete' => function ($url, $model) {
                    return Html::a('', ['delete', 'id'=>  common\components\GeneralComponent::encrypt($model->question_id)], ['class'=>'glyphicon glyphicon-trash',
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