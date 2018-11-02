<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Trainer Videos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="cmsbanner">
    <img src="<?php echo Yii::$app->homeUrl;?>images/trainer_video.jpg" class="img-responsive" alt="">
    
</div>


<section class="brdcum-section">
    <div class="container" style="padding:0px;">  
         <ul class="brdcum">
            	<li><a href="<?php echo Yii::$app->homeUrl;?>">Home</a></li>
                <li>Trainer Videos</li>
            </ul>
        
      
</div>
</section>
<div class="trainers-filter-wrap">
	<div class="container">   
    	<div class="filter-inner">  
        <button class="btn btn-trainers-filter"><span class="fa fa-filter"></span> Filter</button>
        <div class="trainers-filter-box">
        		<form>
                	<div class="form-group">
                        <label for="exampleSelect1">Workout Type</label>
                        <select class="form-control" id="">
                            <option></option>
                          <option>Strength Training</option>
                          <option>Cardio</option>
                          <option>Yoga</option>
                          <option>Spin Classes</option> 
                          <option>Stretching</option> 
                          <option>Performance Training</option> 
                          <option>Speed Training</option> 
                        </select>
 					 </div>
                     <div class="form-group">
                        <label for="exampleSelect1">Trainer</label>
                        <input class="form-control" type="text" />
 					 </div>
                              <div class="form-group">
                        <label for="exampleSelect1">Title</label>
                        <input class="form-control" type="text" />
 					 </div>
                      <div class="filter-btn"> 
                                <button type="submit" class="btn ">Submit</button>
                                <button type="submit" class="btn btn-dark">Reset</button>
 					 </div>
  				</form>
                
        </div>
        </div>
    </div>
</div>
                
<section class="innerpage-section">
 	 <div class="container">
     	<div class="row"> 
              <div class="col-sm-4"> 
              		 <div class="box-wrap wow fadeInUp"> 
              		 		<div class="box-img"> 
                            <div class="box-img-center"> 
              		 			<img src="<?php echo Yii::$app->homeUrl;?>images/trnee6.jpg" />
                                	<a href="#" class="play-icon"><img src="<?php echo Yii::$app->homeUrl;?>images/play-icon.png"  /></a>
                                </div>
     		  		 		</div>
                            <div class="box-contan"> 
              		 			 	<div class="box-left"> 
              		 			 		<h4>Video 1</h4>
                                        <p>Strength Training</p>
     		  		 				</div>
                                    <div class="box-right"> 
              		 			 		<div class="user-roundet">
                                        		<img src="<?php echo Yii::$app->homeUrl;?>images/user-icon.jpg" />
                                        </div>
                                        <div class="tr-info">
                                            <h4>Price <span>$20</span></h4>
                                            <p>John Adams</p>
                                        </div>
     		  		 				</div>
     		  		 		</div>
     		  		 </div>
     		  </div>
              <div class="col-sm-4"> 
              		 <div class="box-wrap wow fadeInUp"> 
              		 		<div class="box-img">
                            <div class="box-img-center"> 
              		 			<img src="<?php echo Yii::$app->homeUrl;?>images/fitness-class1.jpg" />
                                <a href="#" class="play-icon"><img src="<?php echo Yii::$app->homeUrl;?>images/play-icon.png"  /></a>
                                </div>
     		  		 		</div>
                            <div class="box-contan"> 
              		 			 	<div class="box-left"> 
              		 			 		<h4>Video 2</h4>
                                        <p>Cardio</p>
     		  		 				</div>
                                    <div class="box-right"> 
              		 			 		<div class="user-roundet">
                                        		<img src="<?php echo Yii::$app->homeUrl;?>images/user-icon.jpg" />
                                        </div>
                                        <div class="tr-info">
                                            <h4>Price <span>$15</span></h4>
                                            <p>Lily Adams</p>
                                        </div>
     		  		 				</div>
     		  		 		</div>
     		  		 </div>
     		  </div>
              <div class="col-sm-4"> 
              		 <div class="box-wrap wow fadeInUp"> 
              		 		<div class="box-img"> 
                            <div class="box-img-center"> 
              		 			<img src="<?php echo Yii::$app->homeUrl;?>images/trnee7.jpg" />
                                <a href="#" class="play-icon"><img src="<?php echo Yii::$app->homeUrl;?>images/play-icon.png"  /></a>
                                </div>
     		  		 		</div>
                            <div class="box-contan"> 
              		 			 	<div class="box-left"> 
              		 			 		<h4>Video 3</h4>
                                        <p>Strength Training</p>
     		  		 				</div>
                                    <div class="box-right"> 
              		 			 		<div class="user-roundet">
                                        		<img src="<?php echo Yii::$app->homeUrl;?>images/user-icon.jpg" />
                                        </div>
                                        <div class="tr-info">
                                           <h4>Price <span>$40</span></h4>
                                            <p>John Adams</p>
                                        </div>
     		  		 				</div>
     		  		 		</div>
     		  		 </div>
     		  </div>
              
              <div class="col-sm-4"> 
              		 <div class="box-wrap wow fadeInUp"> 
              		 		<div class="box-img">
                            <div class="box-img-center"> 
              		 			<img src="<?php echo Yii::$app->homeUrl;?>images/video_bg.jpg" />
                                <a href="#" class="play-icon"><img src="<?php echo Yii::$app->homeUrl;?>images/play-icon.png"  /></a>
                                </div>
     		  		 		</div>
                            <div class="box-contan"> 
              		 			 	<div class="box-left"> 
              		 			 		<h4>Video 4</h4>
                                        <p>Stretching</p>
     		  		 				</div>
                                    <div class="box-right"> 
              		 			 		<div class="user-roundet">
                                        		<img src="<?php echo Yii::$app->homeUrl;?>images/user-icon.jpg" />
                                        </div>
                                        <div class="tr-info">
                                            <h4>Price <span>$60</span></h4>
                                            <p>Jackson Hill</p>
                                        </div>
     		  		 				</div>
     		  		 		</div>
     		  		 </div>
     		  </div>
              <div class="col-sm-4"> 
              		 <div class="box-wrap wow fadeInUp"> 
              		 		<div class="box-img"> 
                            <div class="box-img-center"> 
              		 			<img src="<?php echo Yii::$app->homeUrl;?>images/trnee5.jpg" />
                                <a href="#" class="play-icon"><img src="<?php echo Yii::$app->homeUrl;?>images/play-icon.png"  /></a>
                                </div>
     		  		 		</div>
                            <div class="box-contan"> 
              		 			 	<div class="box-left"> 
              		 			 		<h4>Video 5</h4>
                                        <p>Yoga</p>
     		  		 				</div>
                                    <div class="box-right"> 
              		 			 		<div class="user-roundet">
                                        		<img src="<?php echo Yii::$app->homeUrl;?>images/user-icon.jpg" />
                                        </div>
                                        <div class="tr-info">
                                            <h4>Price <span>$75</span></h4>
                                            <p>Herry Jack</p>
                                        </div>
     		  		 				</div>
     		  		 		</div>
     		  		 </div>
     		  </div>
              
              <div class="col-sm-4"> 
              		 <div class="box-wrap wow fadeInUp"> 
              		 		<div class="box-img"> 
                            <div class="box-img-center"> 
              		 			<img src="<?php echo Yii::$app->homeUrl;?>images/trnee4.jpg" />
                                <a href="#" class="play-icon"><img src="<?php echo Yii::$app->homeUrl;?>images/play-icon.png"  /></a>
                                </div>
     		  		 		</div>
                            <div class="box-contan"> 
              		 			 	<div class="box-left"> 
              		 			 		<h4>Video 6</h4>
                                        <p>Performance Training</p>
     		  		 				</div>
                                    <div class="box-right"> 
              		 			 		<div class="user-roundet">
                                        		<img src="<?php echo Yii::$app->homeUrl;?>images/user-icon.jpg" />
                                        </div>
                                        <div class="tr-info">
                                            <h4>Price <span>$35</span></h4>
                                            <p>Wil Adams</p>
                                        </div>
     		  		 				</div>
     		  		 		</div>
     		  		 </div>
     		  </div>
     	</div>
     </div>
</section>
 


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
    if (filterRemoveClass) {
        $(".trainers-filter-box").removeClass("filter-open");
    }
    filterRemoveClass = true;
});

$("html").click(function () {
    if (filterRemoveClass) {
        $(".trainers-filter-box").removeClass("filter-open");
    }
    filterRemoveClass = true;
});

  
    
',  yii\web\View::POS_READY);?>