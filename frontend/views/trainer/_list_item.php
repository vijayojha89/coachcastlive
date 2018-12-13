<?php

// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;

$gnl = new \common\components\GeneralComponent();


?>
<div class="col-sm-4"> 
    <div class="box-wrap wow fadeInUp"> 
        <div class="box-img"> 
            <div class="box-img-center"> 
            <img src="<?php echo $gnl->image_not_found_hb($model->profile_photo, 'profile_photo',1); ?>" />
            </div>
        </div>
        <div class="box-contan"> 
            <div class="box-left">
                <h4><?php echo $model->first_name.' '.$model->last_name; ?></h4>
                <!--<p class="blog_list_date">Category : <?php echo @$workoutdetail['name']; ?></p>-->
               
             </div>
            <div class="box-right"> 

                <div class="tr-info">
                    <div class="video_edit class_prize" style="text-align:center;">
                        
                        <span class="btnsbox" style="float:none;">
                          <a class="delete_vdo" title="View Profile"  href="<?php echo Url::to(['coach-profile/view', 'id' => \common\components\GeneralComponent::encrypt($model->id)]); ?>">View Profile</a>           
                        <span>
                    </div>
                    

                </div>
            </div>
        </div>
    </div>
</div>

