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

$this->title = 'My Videos';

?>

<!-- Start Inner Banner area -->
<div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>Videos</h2>
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
                            <h2 class="section-title-default2 title-bar-high2">Videos</h2>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" class="filterbtn"><i class="fa fa-filter"></i> Filter</a>
                            <?= 
                                ListView::widget([
                                    'dataProvider' => $dataProvider,
                                    'options' => [
                                        'tag' => 'div',
                                        'class' => 'search-result-pg row trainer_dashbord_video_section',
                                        'id' => 'list-wrapper',
                                    ],
                                    'layout' => "\n{items}\n{pager}",
                                    'itemView' => '_list_item',
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
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <h4 class="modal-title">Search filter</h4>
      </div>
      <div class="modal-body">
      
      <div class="searchbox">
               <div class="form-group">
               <label class="control-label" for="">Search</label>
             <input type="text" class="form-control" name="" placeholder="Search...">
                                    </div>
                                    <div class="form-group">
                                    <label class="control-label" for="">Categories</label>
                            <select class="form-control" name="">
                            <option value=""></option>
                            <option value="1">Sport</option>
                            <option value="2">Strength Training</option>
                            </select>

                            
                            </div>

                        <div class="searcbtn">
                             <button type="button" class="btn">Search</button>
      <a class="btn btn-dark" data-dismiss="modal" href="javascript:void(0)">Close</a>
                            </div>
                            </div>

      </div>
     
      
    </div>

  </div>
</div>