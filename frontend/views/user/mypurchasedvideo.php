<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;



/* @var $this yii\web\View */
/* @var $searchModel app\models\TrainerVideoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My Purchased Videos';

?>

<!-- Start Inner Banner area -->
<div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>My Purchased Videos</h2>
                    </div>
                </div>
            </div>
 </div>
<!-- End Inner Banner area -->


<div class="online-store-grid padding-space">
            <div class="container">
                <div class="row">
                    <?php echo $this->render('//user/_left_sidebar.php'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-9">
                        <div class="pro-rgt-top">
                            <?php echo $this->render('//user/_user_header.php'); ?>
                        </div>
                        <div class="whatclientsay">
                            <h2 class="section-title-default2 title-bar-high2">My Purchased Videos</h2>
                            
                            <?= 
                                ListView::widget([
                                    'dataProvider' => $dataProvider,
                                    'options' => [
                                        'tag' => 'div',
                                        'class' => 'search-result-pg row trainer_dashbord_video_section',
                                        'id' => 'list-wrapper',
                                    ],
                                    'layout' => "\n{items}\n{pager}",
                                    'itemView' => '_mypurchasedvideo_list_item',
                                ]);
                                    

                                ?>

                        </div>
                    </div>
                </div>
            </div>
</div>






<!-- Modal -->
<div id="myModal" class="modal fade filterpopup" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Filter</h4>
      </div>
      <div class="modal-body">
      
      <div class="searchbox">
      <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>


 <?= $form->field($searchModel, 'title') ?>


               <?php
        
        $workoutdetail = Yii::$app->db->createCommand("SELECT * FROM workout_type")->queryAll();
		$listData=ArrayHelper::map($workoutdetail,'workout_type_id','name');
		echo $form->field($searchModel, 'workout_type_id')->dropDownList($listData, ['prompt'=>'']);
	?>


                        <div class="searcbtn">
                             <?= Html::submitButton('Search', ['class' => 'btn']) ?>
      <a class="btn btn-dark" href="<?php echo Url::to(['trainer-video/index']); ?>">Reset</a>
                            </div>
                            
    <?php ActiveForm::end(); ?>
                            </div>

      </div>
     
      
    </div>

  </div>
</div>
