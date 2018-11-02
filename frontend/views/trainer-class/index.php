<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TrainerClassSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Classes';
echo $this->render('//trainer/_trainer_header.php');
?>

<div id="content" class="inner_container trainers_video_dashboard_index">
  <section class="contentsection">
    <div class="container">
         <div class="row">
          <div class="col-xs-6">
           <h1 class="maintitle">Classes</h1>
          </div>
          <div class="col-xs-6">
              <div class="filter-inner trainers_video_dashboard_filter">  
        <button class="btn btn-trainers-filter"><span class="fa fa-filter"></span> Filter</button>
        <div class="trainers-filter-box">

            <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
                	
            

                             <?php
                                                                echo $form->field($searchModel, 'workout_type_id')
                                                                        ->dropDownList(ArrayHelper::map(\common\models\WorkoutType::find()
                                                                                        ->where(['status' => 1])->asArray()->all(), 'workout_type_id', 'name'), ['prompt' => '']);
                                                                ?>
    
            
                  
    <?= $form->field($searchModel, 'title') ?>

            
                     
                              
                      <div class="filter-btn"> 
                               
                                <?= Html::submitButton('Search', ['class' => 'btn']) ?>
                         <a href="<?php echo Url::to(['trainer-class/index']);?>" class="btn btn-dark">Reset</a>                                
 					 </div>
  				<?php ActiveForm::end(); ?>
                
        </div>
        </div>
              
              <div class="provideo-add"> 
                  <a class="add_vbtn" href="<?php echo Url::to(['trainer-class/create']);?>">
                      <span><i class="fa fa-plus-circle"></i> Add New</span>
                  </a>
             </div>
          </div>
          
     </div>
    </div>
      <div class="container">
      <?php Pjax::begin(); ?>
          <?= 
ListView::widget([
    'dataProvider' => $dataProvider,
    'options' => [
        'tag' => 'div',
        'class' => 'search-result-pg trainer_dashbord_blog row',
        'id' => 'list-wrapper',
    ],
    'layout' => "\n{items}\n{pager}",
    'itemView' => '_list_item',
]);
    

?>
          
<?php Pjax::end(); ?>
          
    </div>
      
      
  </section>
</div>
<?php 

$this->registerJs('
    

var filterRemoveClass = true;
$(".btn-trainers-filter").click(function () {
    $(".trainers-filter-box").toggleClass("filter-open");
    filterRemoveClass = false;
});

$(".trainers-filter-box").click(function() {
    filterRemoveClass = false;
});

$(".trainers-filter-box .btn").click(function () {
 $("#overlays").show();
   
    filterRemoveClass = false;
});


$("html").click(function () {
    if (filterRemoveClass) {
        $(".trainers-filter-box").removeClass("filter-open");
    }
    filterRemoveClass = true;
});

  
    
',  yii\web\View::POS_READY);?>