<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use kartik\rating\StarRating;

$mnl = new \common\components\MasterComponent();
$data = $mnl->get_review_option('tutor');

/* @var $this yii\web\View */
/* @var $searchModel common\models\ReviewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tutor Reviews');
$this->params['breadcrumbs'][] = $this->title;

if(isset($_REQUEST['Review']['posted_for'])){
$user_value_for = $_REQUEST['Review']['posted_for'];}
else{$user_value_for = '';}
if(isset($_REQUEST['Review']['posted_by'])){
$user_value_by = $_REQUEST['Review']['posted_by'];}
else{$user_value_by = '';}
?>
<style>
    .clear-rating.clear-rating-active{
        display: none;
    }
</style>
<?php Pjax::begin(); ?><div class="review-index">
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
            /*Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create-tutor-review'],
            ['class' => 'btn btn-success']) . ' ' .  
             * 
             */          
            Html::a('<i class="glyphicon glyphicon-refresh"></i>', ['tutor-review'], ['class' => 'btn btn-info']),
            
        ],
        'toolbar' => [     
    '{toggleData}'
],
    
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
[
                'attribute' => 'Question_id',
                'enableSorting'=>FALSE,
                'label'=>'Question',
                'value' => function ($data) {
                            return implode(' ', ArrayHelper::map(common\models\Question::findAll(array('question_id' => $data['question_id'])), 'question_id', 'title'));
                        },
                                'filter' =>FALSE,
//                'filter' => Html::activeDropDownList($searchModel, 'review_opt',
//                                ArrayHelper::map(common\models\ReviewOption::find()->where(['role'=>'student','status'=>1])->asArray()->all(), 'review_option_id', 'option'), ['class' => 'form-control', 'prompt' => '']),
            ],
[
                'attribute' => 'review_opt',
                'enableSorting'=>FALSE,
                'value' => function ($data) {
                            return implode(' ', ArrayHelper::map(common\models\ReviewOption::findAll(array('review_option_id' => $data['review_opt'])), 'review_option_id', 'option'));
                        },
                                'filter' =>FALSE,
//                'filter' => Html::activeDropDownList($searchModel, 'review_opt',
//                                ArrayHelper::map(common\models\ReviewOption::find()->where(['role'=>'tutor','status'=>1])->asArray()->all(), 'review_option_id', 'option'), ['class' => 'form-control', 'prompt' => '']),
            ],
[
                'attribute' => 'comment',
                'enableSorting'=>FALSE,
            ],

[
                        'attribute' => 'posted_by',
                        'value' => function ($data) {
                            return implode(' ', ArrayHelper::map(common\models\User::find()
                                               ->select(new \yii\db\Expression("CONCAT(`first_name`, '  ', `last_name`) as name"))
                                               ->where(['id' => $data['posted_by']])->asArray()->all(), 'id', 'name'));
                        },
                        'format' => 'raw',
                        'enableSorting'=>FALSE,
                'filter' => '<input type="text" class="form-control" name="Review[posted_by]" value="'.$user_value_by.'">',

                    ],[
                'attribute' => 'posted_for',
                        'label' => 'Posted For',
                'value' => function ($data) {
                            return implode(' ', ArrayHelper::map(common\models\User::find()
                                               ->select(new \yii\db\Expression("CONCAT(`first_name`, '  ', `last_name`) as name"))
                                               ->where(['id' => $data['posted_for']])->asArray()->all(), 'id', 'name'));
                        },
                'enableSorting'=>FALSE,
                'filter' => '<input type="text" class="form-control" name="Review[posted_for]" value="'.$user_value_for.'">',  
            ],
[
                'attribute' => 'rating',
                'enableSorting'=>FALSE,
                'filter'=>FALSE,
                'value'=>function ($data) {
                           return StarRating::widget([
                                    'name' => 'rating',
                                    'value' => $data['rating'],
                                    'pluginOptions' => ['displayOnly' => true,'size'=>'small']
                            ]);
                                    },
                             'format' => 'raw',
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
                return Html::a('', 'tutor-review'.$string, [                                                  
                            'class'=>$icon,'onClick'=>'active_deactive(\''.$url.'\')',                                  
                ]);
            },
                            'update' => function ($url, $model) {
                    return Html::a('',['update-tutor-review', 'id'=>  common\components\GeneralComponent::encrypt($model->review_id)],
				      ['class'=>'glyphicon glyphicon-pencil', 'data-pjax'=>0]);
            }, 
                     'delete' => function ($url, $model) {
                    return Html::a('', ['delete-tutor', 'id'=>  common\components\GeneralComponent::encrypt($model->review_id)], ['class'=>'glyphicon glyphicon-trash',
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