<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Trainer Videos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="cmsbanner">
    <img src="<?php echo Yii::$app->homeUrl;?>images/our_trainers.jpg" class="img-responsive" alt="">
  
</div>


<section class="brdcum-section">
    <div class="container" style="padding:0px;">  
         <ul class="brdcum">
            	<li><a href="<?php echo Yii::$app->homeUrl;?>">Home</a></li>
                <li>Our Trainers</li>
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
                        <label for="exampleSelect1">Name</label>
                        <input class="form-control" type="text" />
 					 </div>
                              <div class="form-group">
                        <label for="exampleSelect1">City</label>
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
  
<section class="trainer-section trainy-page" style="margin-bottom: 30px;">
	 
     <div class="container">
     <div class="row">
    <div class="tranee-wrap">
    	<ul>
        	<li class="tranee-box wow fadeInUp"> 
                <div class="tranee-img"><img src="<?php echo Yii::$app->homeUrl;?>images/trnee1.jpg"  />
                	<span class="tranee-name">
                		<h3>Hazel Wolfe</h3>
                        <div class="tranee-follow">
                        	<a href="#" class="icon-button twitter"><i class="icon-twitter fa fa-twitter"></i><span></span></a>
                            <a href="#" class="icon-button facebook"><i class="icon-facebook fa fa-facebook"></i><span></span></a>
                            <a href="#" class="icon-button google-plus"><i class="icon-google-plus fa fa-google"></i><span></span></a>
                        </div>
                  	</span>
                </div>
            </li>
           <li class="tranee-box wow fadeInUp"> 
                <div class="tranee-img"><img src="<?php echo Yii::$app->homeUrl;?>images/trnee2.jpg"  />
                	<span class="tranee-name">
                		<h3>Franklin Howard</h3>
                        <div class="tranee-follow">
                        	<a href="#" class="icon-button twitter"><i class="icon-twitter fa fa-twitter"></i><span></span></a>
                            <a href="#" class="icon-button facebook"><i class="icon-facebook fa fa-facebook"></i><span></span></a>
                            <a href="#" class="icon-button google-plus"><i class="icon-google-plus fa fa-google"></i><span></span></a>
                        </div>
                  	</span>
                </div>
            </li>
             <li class="tranee-box wow fadeInUp"> 
                <div class="tranee-img"><img src="<?php echo Yii::$app->homeUrl;?>images/trnee3.jpg"  />
                	<span class="tranee-name">
                		<h3>Thomas Adams</h3>
                        <div class="tranee-follow">
                        	<a href="#" class="icon-button twitter"><i class="icon-twitter fa fa-twitter"></i><span></span></a>
                            <a href="#" class="icon-button facebook"><i class="icon-facebook fa fa-facebook"></i><span></span></a>
                            <a href="#" class="icon-button google-plus"><i class="icon-google-plus fa fa-google"></i><span></span></a>
                        </div>
                  	</span>
                </div>
            </li>
            <li class="tranee-box wow fadeInUp"> 
                <div class="tranee-img"><img src="<?php echo Yii::$app->homeUrl;?>images/trnee4.jpg"  />
                	<span class="tranee-name">
                		<h3>Elois Martinez</h3>
                        <div class="tranee-follow">
                        	<a href="#" class="icon-button twitter"><i class="icon-twitter fa fa-twitter"></i><span></span></a>
                            <a href="#" class="icon-button facebook"><i class="icon-facebook fa fa-facebook"></i><span></span></a>
                            <a href="#" class="icon-button google-plus"><i class="icon-google-plus fa fa-google"></i><span></span></a>
                        </div>
                  	</span>
                </div>
            </li>
           <li class="tranee-box wow fadeInUp"> 
                <div class="tranee-img"><img src="<?php echo Yii::$app->homeUrl;?>images/trnee5.jpg"  />
                	<span class="tranee-name">
                		<h3>Howard Keith</h3>
                        <div class="tranee-follow">
                        	<a href="#" class="icon-button twitter"><i class="icon-twitter fa fa-twitter"></i><span></span></a>
                            <a href="#" class="icon-button facebook"><i class="icon-facebook fa fa-facebook"></i><span></span></a>
                            <a href="#" class="icon-button google-plus"><i class="icon-google-plus fa fa-google"></i><span></span></a>
                        </div>
                  	</span>
                </div>
            </li>
            <li class="tranee-box wow fadeInUp"> 
                <div class="tranee-img"><img src="<?php echo Yii::$app->homeUrl;?>images/trnee6.jpg"  />
                	<span class="tranee-name">
                		<h3>Russell Brock</h3>
                        <div class="tranee-follow">
                        	<a href="#" class="icon-button twitter"><i class="icon-twitter fa fa-twitter"></i><span></span></a>
                            <a href="#" class="icon-button facebook"><i class="icon-facebook fa fa-facebook"></i><span></span></a>
                            <a href="#" class="icon-button google-plus"><i class="icon-google-plus fa fa-google"></i><span></span></a>
                        </div>
                  	</span>
                </div>
            </li>
           <li class="tranee-box wow fadeInUp"> 
                <div class="tranee-img"><img src="<?php echo Yii::$app->homeUrl;?>images/trnee7.jpg"  />
                	<span class="tranee-name">
                		<h3>Micheal McCarty</h3>
                        <div class="tranee-follow">
                        	<a href="#" class="icon-button twitter"><i class="icon-twitter fa fa-twitter"></i><span></span></a>
                            <a href="#" class="icon-button facebook"><i class="icon-facebook fa fa-facebook"></i><span></span></a>
                            <a href="#" class="icon-button google-plus"><i class="icon-google-plus fa fa-google"></i><span></span></a>
                        </div>
                  	</span>
                </div>
            </li>
            <li class="tranee-box wow fadeInUp"> 
                <div class="tranee-img"><img src="<?php echo Yii::$app->homeUrl;?>images/trnee1.jpg"  />
                	<span class="tranee-name">
                		<h3>Douglas Mitchell</h3>
                        <div class="tranee-follow">
                        	<a href="#" class="icon-button twitter"><i class="icon-twitter fa fa-twitter"></i><span></span></a>
                            <a href="#" class="icon-button facebook"><i class="icon-facebook fa fa-facebook"></i><span></span></a>
                            <a href="#" class="icon-button google-plus"><i class="icon-google-plus fa fa-google"></i><span></span></a>
                        </div>
                  	</span>
                </div>
            </li>
           <li class="tranee-box wow fadeInUp"> 
                <div class="tranee-img"><img src="<?php echo Yii::$app->homeUrl;?>images/trnee8.jpg"  />
                	<span class="tranee-name">
                		<h3>Lily Adams</h3>
                        <div class="tranee-follow">
                        	<a href="#" class="icon-button twitter"><i class="icon-twitter fa fa-twitter"></i><span></span></a>
                            <a href="#" class="icon-button facebook"><i class="icon-facebook fa fa-facebook"></i><span></span></a>
                            <a href="#" class="icon-button google-plus"><i class="icon-google-plus fa fa-google"></i><span></span></a>
                        </div>
                  	</span>
                </div>
            </li>
        </ul>
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