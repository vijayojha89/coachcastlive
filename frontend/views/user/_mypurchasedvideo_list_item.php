<?php

// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;

$gnl = new \common\components\GeneralComponent();
$workoutdetail = Yii::$app->db->createCommand("SELECT name FROM workout_type WHERE workout_type_id =" . $model->workout_type_id)->queryOne();
?>
<div class="col-sm-4"> 
    <div class="box-wrap wow fadeInUp videolist"> 
        <div class="box-img"> 
            <div class="box-img-center"> 
                <img src="<?php echo $gnl->image_not_found_hb($model->video_image, 'video_image',1); ?>" />
                <a href="<?php echo Url::to(['trainer-video/play', 'id' => \common\components\GeneralComponent::encrypt($model->video_id)]); ?>" class="play-icon"><img src="<?php echo Yii::$app->homeUrl; ?>images/play-icon.png"  /></a>
            </div>
        </div>
        <div class="box-contan"> 
        <img class="img-circle" style="width:100px;height:100px;" src="<?php echo $gnl->image_not_found_hb($model->user->profile_photo, 'profile_photo',1); ?>" alt="User Avatar">
            <div class="box-left"> 
                <h4>Title : <?php echo $model->title; ?></h4>
                <p class="blog_list_date">Coach Name : <?php echo $model->user->first_name.' '.$model->user->last_name; ?></p>
                <p class="blog_list_date">Category : <?php echo @$workoutdetail['name']; ?></p>
                
            </div>
            <div class="box-right"> 
			    <div class="tr-info">
                    <div class="video_edit class_prize">
                            <?php if($model->price > 0){ ?> 
                         <h4>Price <span> $<?php echo $model->price; ?></span></h4>
                            <?php }else{ ?>
                              <h4>Free</h4>      
                            <?php } ?>   
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
