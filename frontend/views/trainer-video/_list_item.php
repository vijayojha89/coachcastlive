<?php

// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;

$gnl = new \common\components\GeneralComponent();
$workoutdetail = Yii::$app->db->createCommand("SELECT name FROM workout_type WHERE workout_type_id =" . $model->workout_type_id)->queryOne();
?>
<div class="col-sm-3"> 
    <div class="box-wrap wow fadeInUp"> 
        <div class="box-img"> 
            <div class="box-img-center"> 
                <img src="<?php echo $gnl->image_not_found_hb($model->video_image, 'video_image',1); ?>" />
                <a href="<?php echo Url::to(['trainer-video/update', 'id' => \common\components\GeneralComponent::encrypt($model->trainer_video_id)]); ?>" class="play-icon"><img src="<?php echo Yii::$app->homeUrl; ?>images/play-icon.png"  /></a>
            </div>
        </div>
        <div class="box-contan"> 
            <div class="box-left"> 
                <h4><?php echo $model->title; ?></h4>
                <p class="blog_list_date">Category : <?php echo @$workoutdetail['name']; ?></p>
            </div>
            <div class="box-right"> 
			    <div class="tr-info">
                    <div class="video_edit class_prize">
                         <h4>Price <span> $<?php echo $model->price; ?></span></h4>
                         <h4>Viewed : 50 Times</span></h4>
                         <span class="btnsbox">
                        <a class="edit_vdo" href="<?php echo Url::to(['trainer-video/update', 'id' => \common\components\GeneralComponent::encrypt($model->trainer_video_id)]); ?>" title="Edit"> <i class="fa fa-edit"></i> </a>
                        <a class="delete_vdo" href="<?php echo Url::to(['trainer-video/delete', 'id' => \common\components\GeneralComponent::encrypt($model->trainer_video_id)]); ?>" onclick="return confirm('Are you sure want to delete?')" title="Delete"><i class="fa fa-remove"></i></a>
</span>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
