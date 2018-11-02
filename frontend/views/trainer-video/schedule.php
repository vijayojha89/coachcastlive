<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TrainerVideoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Schedule';
echo $this->render('//trainer/_trainer_header.php');
?>
<div id="content" class="inner_container">
    <section class="contentsection">
        <div class="container">


            <div class="row">
                <div class="col-xs-6">
                    <h1 class="maintitle">Schedule</h1>
                </div>
                <div class="col-xs-6">
                    <div class="filter-inner trainers_video_dashboard_filter">  
                        <button class="btn btn-trainers-filter"><span class="fa fa-filter"></span> Filter</button>
                        <div class="trainers-filter-box">

                            <form>                	


                                <div class="form-group field-trainerclasssearch-workout_type_id">
                                    <label class="control-label" for="trainerclasssearch-workout_type_id">User</label>
                                    <select class="form-control">
                                        <option value=""></option>
                                        <option value="1">John Smit</option>
                                        <option value="2">Will Harry</option>
                                        <option value="3">Petric Joseph</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>    


                                <div class="form-group">
                                    <label class="control-label" for="trainerclasssearch-title">Date</label>
                                    <input type="text" id="trainerclasssearch-title" class="form-control">

                                    <div class="help-block"></div>
                                </div>



                                <div class="filter-btn"> 

                                    <button type="submit" class="btn">Search</button>                         <a href="javascript:void(0);" class="btn btn-dark">Reset</a>                                
                                </div>
                            </form>                
                        </div>
                    </div>

                   
                </div>

            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-12"> 
                    <div class="pro-tabs-contant">
                        <div class="tabs-notification">

                            <div class="notification-box">
                                <div class="pic-box"> 
                                    <a> <img src="<?php echo Yii::$app->homeUrl;?>uploads/profile_photo/thumb/ajX9E1UeBjLfjCtstzSJr9zT5AYJU79Q.jpg"></a>
                                </div>
                                <div class="info-box">
                                    <div class="info-box-contant">
                                        <a href="#">  <h4>John Smith</h4> </a> 
                                        <p>Lorem ipsum is simply dummytext collaboratively administrate turnkey channels   channels.</p>
                                    </div>
                                    <div class="notification_time"><i class="fa fa-clock-o"></i> 07/06/2017 10:14</div>
                                    <div class="schedule_brn_wrap">
                                        <a class="btn " href="#">Accept</a>
                                        <a class="btn reject_btn" href="#">Reject</a>
                                    </div> 
                                </div> 
                            </div> 

                            <div class="notification-box">
                                <div class="pic-box"> 
                                    <a> <img src="<?php echo Yii::$app->homeUrl;?>uploads/profile_photo/thumb/CmfTJYvvy_N1JuEEf7Qyi3kgzXdy9Rqx.jpg"></a>
                                </div>
                                <div class="info-box">
                                    <div class="info-box-contant">
                                        <a href="#">  <h4>Will Harry</h4> </a> 
                                        <p>Lorem ipsum is simply dummytext collaboratively administrate turnkey channels   channels.</p>
                                    </div>
                                    <div class="notification_time"><i class="fa fa-clock-o"></i> 07/06/2017 10:14</div>
                                    <div class="schedule_brn_wrap">
                                        <a class="btn " href="#">Accept</a>
                                        <a class="btn reject_btn" href="#">Reject</a>
                                    </div>

                                </div> 
                            </div>  

                        </div>
                    </div>
                </div>
            </div>
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
   
    filterRemoveClass = true;
});


$("html").click(function () {
    if (filterRemoveClass) {
        $(".trainers-filter-box").removeClass("filter-open");
    }
    filterRemoveClass = true;
});

  
    
',  yii\web\View::POS_READY);?>