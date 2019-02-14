<?php

// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;

$gnl = new \common\components\GeneralComponent();
$workoutdetail = Yii::$app->db->createCommand("SELECT name FROM workout_type WHERE workout_type_id =" . $model->workout_type_id)->queryOne();

?>
<?php //echo Url::to(['user/profile','id'=>$model->id]); ?>
<div class="col-sm-4"> 
    <div class="box-wrap wow fadeInUp"> 
        <div class="box-img"> 
            <div class="box-img-center"> 
                <img src="<?php echo $gnl->image_not_found_hb($model->class_image, 'class_image',1); ?>" />
            </div>
        </div>
        <div class="box-contan"> 
            <div class="box-left">
            <img class="img-circle" style="width:100px;height:100px;" src="<?php echo $gnl->image_not_found_hb($model->user->profile_photo, 'profile_photo',1); ?>" alt="User Avatar">

                <h4><?php echo $model->title; ?></h4>
                <!--<p class="blog_list_date">Coach Name : <?php echo $model->user->first_name.' '.$model->user->last_name; ?></p>-->
                <p class="blog_list_date">Category : <?php echo @$workoutdetail['name']; ?></p>
                <!--<p class="blog_list_date">Start Date : <?php echo date('d M Y',strtotime($model->start_date));?></p>
                <p class="blog_list_date">End Date : <?php echo date('d M Y',strtotime($model->end_date));?></p>
                <p class="blog_list_date">Time : <?php echo date('h:i A',strtotime($model->time));?></p>-->
                <p>
                    <span style="text-align:right;"><a href="<?php echo Url::to(['trainer-class/livesession', 'id' => \common\components\GeneralComponent::encrypt($model->class_id)]); ?>" class="btn btn-danger"><i class="fa fa-video-camera"></i>&nbsp;Live Session</a></span>
                </p>
             </div>
            <div class="box-right"> 

                <div class="tr-info">
                    <div class="video_edit class_prize">
                        <h4>Price <span> $<?php echo $model->price; ?></span></h4>
                        <span class="btnsbox">
                           <a class="delete_vdo" title="Detail"  href="<?php echo Url::to(['trainer-class/view', 'id' => \common\components\GeneralComponent::encrypt($model->class_id),'isjoin'=>'1']); ?>">Detail</a>
                        <span>
                    </div>
                    

                </div>
            </div>
        </div>
    </div>
</div>
