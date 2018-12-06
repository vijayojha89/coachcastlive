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
            <a href="#"><?php echo $model->first_name.' '.$model->last_name; ?></a>
        </div>
        
    </div>
</div>
