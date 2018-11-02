<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use kartik\date\DatePicker;
use kartik\export\ExportMenu; 


/* @var $this yii\web\View */
/* @var $searchModel common\models\QuestionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Reconciliation');
$this->params['breadcrumbs'][] = $this->title;

if(isset($_REQUEST['Question']['created_by'])){
$user_value_by = $_REQUEST['Question']['created_by'];}
else{$user_value_by = '';}
if(isset($_REQUEST['Question']['confirm_select_tutor'])){
$user_value_tutor = $_REQUEST['Question']['confirm_select_tutor'];}
else{$user_value_tutor = '';}
if(isset($_REQUEST['Question']['completed_date'])){
$model->completed_date = $_REQUEST['Question']['completed_date'];}
else{$model->completed_date = date("M Y");}
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

<?php Pjax::begin(); ?><div class="question-index">
    <div class="reconciliation_form">
<!--==========================================start==============================================================-->

<div class="post-form">
 <?php $form = ActiveForm::begin(['method' => 'get','options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="form-group field-reconciliation-created_date">
    <?= $form->field($model, 'completed_date')->widget(DatePicker::classname(), [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'startView'=>'year',
                        'minViewMode'=>'months',
                        'format' => 'MM yyyy'
                    ]
                ])->label('Select Month and Year') ?>
</div>
    
    <div class="form-group" id="button_update_delete">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Search') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Reset', ['reconciliation/index'], ['class' => 'btn btn-default','data-pjax'=>'false']);?>
    </div>

  <?php ActiveForm::end(); ?>
 

</div>

<div style="clear:both;"></div>
<!--==============================================end==========================================================-->    
<?php
$questions = \common\models\Question::find()->where(['question_status'=>[2,4,5,6,7]])
                                            ->andWhere(['!=', 'status', 2])
                                            ->andWhere(['=','DATE_FORMAT(completed_date, "%M %Y")',$model->completed_date])
                                            ->asArray()->all();
 $i = 0;
        foreach ($questions as $value) {
                    $listarray[$i] = $value['confirm_bid']-$value['discount_price'];
                    $i++;
                } 
$earned = array_sum($listarray);
if($earned == ''){$earned = 0;}
?>
<div class="ttl_total">
    <b>Total Earned :</b> £ <?= $earned ?> 
</div>  
</div>   
<?php
$gridColumns = [
                    [
                'label' => 'Transaction ID',
                'value' => function ($data) {
                            return implode(' ', ArrayHelper::map(common\models\Transaction::find()
                                               ->where(['question_id' => $data['question_id']])->asArray()->all(), 'transaction_id', 'studypad_txn_id'));
                        },
                'enableSorting'=>FALSE,
            ],          
[
                'attribute' => 'question_id',
                'enableSorting'=>FALSE,
            ],
[
                        'attribute' => 'created_by',
                        'enableSorting'=>FALSE,
                        'label' => 'Student',
                        'value' => function ($data) {
                            return implode(' ', ArrayHelper::map(common\models\User::find()
                                               ->select(new \yii\db\Expression("CONCAT(`first_name`, '  ', `last_name`) as name"))
                                               ->where(['id' => $data['created_by']])->asArray()->all(), 'id', 'name'));
                        },
                'filter' => '<input type="text" class="form-control" name="Question[created_by]" value="'.$user_value_by.'">',
                    ],  
[
                        'attribute' => 'confirm_select_tutor',
                        'enableSorting'=>FALSE,
                        'label' => 'Tutor',
                        'value' => function ($data) {
                            return implode(' ', ArrayHelper::map(common\models\User::find()
                                               ->select(new \yii\db\Expression("CONCAT(`first_name`, '  ', `last_name`) as name"))
                                               ->where(['id' => $data['confirm_select_tutor']])->asArray()->all(), 'id', 'name'));
                        },
                'filter' => '<input type="text" class="form-control" name="Question[confirm_select_tutor]" value="'.$user_value_tutor.'">',
                    ],                                  

                [
                        'label' => Yii::t('app', 'Transaction Date'),
                        'enableSorting'=>FALSE,
                        'attribute' => 'completed_date',
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
                'attribute' => 'admin_commission',
                'enableSorting'=>FALSE,
            ],   
[
                'label' => 'Admin Share',
                 'value' => function ($data) {
                            return (($data['confirm_bid']-$data['discount_price'])*($data['admin_commission']/100));
                        },
                'enableSorting'=>FALSE,
                'pageSummary'=>true,
            ],
[
                'label' => 'Tutor Share',
                 'value' => function ($data) {
                            return ($data['confirm_bid']-$data['discount_price'])-(($data['confirm_bid']-$data['discount_price'])*($data['admin_commission']/100));
                        },
                'enableSorting'=>FALSE,
                'pageSummary'=>true,
            ],    
                ];

?>                     
    <?php
    echo $exp_menu = "<ul class='csv_ul'>" . ExportMenu::widget([
                                                                    'dataProvider' => $dataProvider,
                                                                    'columns' => $gridColumns,
                                                                    'filename' => 'reconciliation_' . date("Y-m-d"),
                                                                    'target' => ExportMenu::TARGET_SELF,
                                                                    'asDropdown' => false,
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
                                                                                                'options' => ['title' => 'Reconciliation'],
                                                                                            ],
                                                                                        ]
                                                              ]). "</ul><div style='clear:both;'></div>";
?>     
<div class="panel panel-default">
		<div class="panel-body">
                  
                    
                    
<!--    <h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   
    <?= GridView::widget([
        'export'=>false,
        'responsive'=>true,
        'pjax'=>FALSE,
        'resizableColumns'=>true,        
        'showPageSummary' => true,
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
                'label' => 'Transaction ID',
                'value' => function ($data) {
                            return implode(' ', ArrayHelper::map(common\models\Transaction::find()
                                               ->where(['question_id' => $data['question_id']])->asArray()->all(), 'transaction_id', 'studypad_txn_id'));
                        },
                'enableSorting'=>FALSE,
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
                        'label' => 'Student',
                        'value' => function ($data) {
                            return implode(' ', ArrayHelper::map(common\models\User::find()
                                               ->select(new \yii\db\Expression("CONCAT(`first_name`, '  ', `last_name`) as name"))
                                               ->where(['id' => $data['created_by']])->asArray()->all(), 'id', 'name'));
                        },
                'filter' => '<input type="text" class="form-control" name="Question[created_by]" value="'.$user_value_by.'">',
                    ],  
[
                        'attribute' => 'confirm_select_tutor',
                        'enableSorting'=>FALSE,
                        'label' => 'Tutor',
                        'value' => function ($data) {
                            return implode(' ', ArrayHelper::map(common\models\User::find()
                                               ->select(new \yii\db\Expression("CONCAT(`first_name`, '  ', `last_name`) as name"))
                                               ->where(['id' => $data['confirm_select_tutor']])->asArray()->all(), 'id', 'name'));
                        },
                'filter' => '<input type="text" class="form-control" name="Question[confirm_select_tutor]" value="'.$user_value_tutor.'">',
                    ],                                  

                [
                        'label' => Yii::t('app', 'Transaction Date'),
                        'enableSorting'=>FALSE,
                        'attribute' => 'completed_date',
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
                'attribute' => 'admin_commission',
                'enableSorting'=>FALSE,
            ],   
[
                'label' => 'Admin Share',
                 'value' => function ($data) {
                            return (($data['confirm_bid']-$data['discount_price'])*($data['admin_commission']/100));
                        },
                'enableSorting'=>FALSE,
                'pageSummary'=>true,
            ],
[
                'label' => 'Tutor Share',
                 'value' => function ($data) {
                            return ($data['confirm_bid']-$data['discount_price'])-(($data['confirm_bid']-$data['discount_price'])*($data['admin_commission']/100));
                        },
                'enableSorting'=>FALSE,
                'pageSummary'=>true,
            ],                                
/*
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
                                    ['2' => 'Completed','4'=>'Cancelled'], ['class' => 'form-control', 'prompt' => 'All']),
                        'enableSorting'=>FALSE,
                        'pageSummary'=>'Earned Amount (£)',
                        'pageSummaryOptions'=>['class'=>'text-right text-warning'],
                    ],     
 * 
 */

            
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