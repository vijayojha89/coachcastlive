<?php

// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;

$gnl = new \common\components\GeneralComponent();


?>
<?php //echo Url::to(['user/profile','id'=>$model->id]); ?>
<div class="col-sm-4"> 
    <div class="box-wrap wow fadeInUp"> 
        <div class="box-img"> 
            <div class="box-img-center"> 
                <img src="<?php echo $gnl->image_not_found_hb($model->profile_photo, 'profile_photo',1); ?>" />
            </div>
        </div>
        <div class="box-contan"> 
            <div class="box-right"> 
                <div class="tr-info">
                    <div class="video_edit class_prize">
                        <h4><?php echo $model->first_name.' '.$model->last_name; ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>