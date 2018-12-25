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

$this->title = 'Coach Profile';
$gnl = new \common\components\GeneralComponent();
?>
<style>
.mt-10{
    margin-top:10px;
}
</style>

<!-- Start Inner Banner area -->
<div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>Coach Profile</h2>
                    </div>
                </div>
            </div>
 </div>
<!-- End Inner Banner area -->



<div class="online-store-grid padding-space">
            <div class="container">
                <div class="row">
                    <?php echo $this->render('//coach-profile/_left_sidebar.php',['model'=>$model]); ?>
                    <div class="col-lg-9 col-md-9 col-sm-9">
                        <div class="pro-rgt-top">
                            <?php //echo $this->render('//coach-profile/_user_header.php',['model'=>$model]); ?>
                        </div>
                        <div class="whatclientsay findcoachsection">
                            <div class="coachClassSection ">
                            <h2 class="section-title-default2 title-bar-high2">My Classes</h2>
                             <?php if($coachClasses) { ?>
                                <div class="gym-carousel nav-control-middle" data-loop="true" data-items="3" data-margin="15"
                            data-autoplay="false" data-autoplay-timeout="10000" data-smart-speed="2000" data-dots="false"
                            data-nav="true" data-nav-speed="false" data-r-x-small="1" data-r-x-small-nav="true"
                            data-r-x-small-dots="false" data-r-x-medium="2" data-r-x-medium-nav="true"
                            data-r-x-medium-dots="false" data-r-small="3" data-r-small-nav="true" data-r-small-dots="false"
                            data-r-medium="3" data-r-medium-nav="true" data-r-medium-dots="false" data-r-large="3"
                            data-r-large-nav="true" data-r-large-dots="false">

                            <?php foreach($coachClasses as $value) { ?>
                            

                            <div> 
    <div class="box-wrap wow fadeInUp"> 
        <div class="box-img"> 
            <div class="box-img-center"> 
                <img src="<?php echo $gnl->image_not_found_hb($value['class_image'], 'class_image',1); ?>" />
            </div>
        </div>
        <div class="box-contan"> 
            <div class="box-left">
          
                <h4><?php echo $value['title']; ?></h4>
                
                <p class="blog_list_date">Category : <?php echo "test"; ?></p>
             </div>
            <div class="box-right"> 

                <div class="tr-info">
                    <div class="video_edit class_prize">
                        <h4>Price <span> $<?php echo $value['price']; ?></span></h4>
                        <span class="btnsbox">
                           <a class="delete_vdo" title="Detail"  href="<?php echo Url::to(['trainer-class/view', 'id' => \common\components\GeneralComponent::encrypt($value['trainer_class_id'])]); ?>">Detail</a>
                        
                        <span>
                    </div>
                    

                </div>
            </div>
        </div>
    </div>
</div>

                            <?php } ?>
                            
                            
                        </div>
                             <?php }else{ ?>
                                No classes found.    
                             <?php } ?>




                           </div>  
                                

                            <div class="coachVideoSection mt-10">
                            
                            <h2 class="section-title-default2 title-bar-high2">My Videos</h2>
                            <?php if($coachVideos) { ?>
                                <div class="gym-carousel nav-control-middle" data-loop="true" data-items="3" data-margin="15"
                            data-autoplay="false" data-autoplay-timeout="10000" data-smart-speed="2000" data-dots="false"
                            data-nav="true" data-nav-speed="false" data-r-x-small="1" data-r-x-small-nav="true"
                            data-r-x-small-dots="false" data-r-x-medium="2" data-r-x-medium-nav="true"
                            data-r-x-medium-dots="false" data-r-small="3" data-r-small-nav="true" data-r-small-dots="false"
                            data-r-medium="3" data-r-medium-nav="true" data-r-medium-dots="false" data-r-large="3"
                            data-r-large-nav="true" data-r-large-dots="false">

                                    <?php foreach($coachVideos as $value){ ?>


                                            
<div> 
    <div class="box-wrap wow fadeInUp videolist"> 
        <div class="box-img"> 
            <div class="box-img-center"> 
                <img src="<?php echo $gnl->image_not_found_hb($value['video_image'], 'video_image',1); ?>" />
                <a href="<?php echo Url::to(['trainer-video/play', 'id' => \common\components\GeneralComponent::encrypt($value['trainer_video_id'])]); ?>" class="play-icon"><img src="<?php echo Yii::$app->homeUrl; ?>images/play-icon.png"  /></a>
            </div>
        </div>
        <div class="box-contan"> 
            <div class="box-left"> 
                <h4>Title : <?php echo $value['title']; ?></h4>
                <p class="blog_list_date">Category : <?php echo "TEST"; ?></p>
                <p>Viewed : <?php echo $value['no_of_view'];?> Times</p>
            </div>
            <div class="box-right"> 
			    <div class="tr-info">
                    <div class="video_edit class_prize">
                            <?php if($value['price'] > 0){ ?> 
                         <h4>Price <span> $<?php echo $value['price']; ?></span></h4>
                            <?php }else{ ?>
                              <h4>Free</h4>      
                            <?php } ?>   
                         <span class="btnsbox">

                         
                            <a class="buyBtn" title="Buy"  href="<?php echo Url::to(['trainer-video/buy', 'id' => \common\components\GeneralComponent::encrypt($value['trainer_video_id'])]); ?>">Buy <i class="fa fa-shopping-cart"></i></a>
                        
</span>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

                                    <?php } ?>

                            </div>
                            
                                <?php }else{ ?>
                                No video found.    
                             <?php } ?>


                            </div>    


                               <div class="coachBlogsSection mt-10">
                            
                            <h2 class="section-title-default2 title-bar-high2">My Blogs</h2>
                            <?php if($coachBlogs) { ?>
                                <div class="gym-carousel nav-control-middle" data-loop="true" data-items="3" data-margin="15"
                            data-autoplay="false" data-autoplay-timeout="10000" data-smart-speed="2000" data-dots="false"
                            data-nav="true" data-nav-speed="false" data-r-x-small="1" data-r-x-small-nav="true"
                            data-r-x-small-dots="false" data-r-x-medium="2" data-r-x-medium-nav="true"
                            data-r-x-medium-dots="false" data-r-small="3" data-r-small-nav="true" data-r-small-dots="false"
                            data-r-medium="3" data-r-medium-nav="true" data-r-medium-dots="false" data-r-large="3"
                            data-r-large-nav="true" data-r-large-dots="false">


                                    <?php foreach($coachBlogs as $value){ ?>

                                        
<div> 
    <div class="box-wrap wow fadeInUp"> 
        <div class="box-img"> 
            <div class="box-img-center"> 
                <img src="<?php echo $gnl->image_not_found_hb($value['blog_image'], 'blog_image', 1); ?>" />

            </div>
        </div>
        <div class="box-contan"> 
            <div class="box-left"> 
                <h4><?php echo $value['title']; ?></h4>
                <p class="blog_list_date">Date : <?php echo date('d M Y',strtotime($value['created_date']));?></p>
                <p class="blog_list_description"><?php echo \common\components\GeneralComponent::wordcutoff($value['description'], 100);?></p>
            </div>
            <div class="box-right"> 

                <div class="tr-info">
                    <div class="video_edit">
                    <span class="btnsbox">
                    <a class="buyBtn" title="Buy"  href="<?php echo Url::to(['blog/view', 'id' => \common\components\GeneralComponent::encrypt($value['blog_id'])]); ?>">View Detail <i class="fa fa-eyes"></i></a>
                        
                        </span>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
                                    <?php } ?>
                                 </div>    
                                 <?php }else{ ?>
                                No blog found.    
                             <?php } ?>    

                             </div>           



                        </div>
                    </div>
                </div>
            </div>
</div>


