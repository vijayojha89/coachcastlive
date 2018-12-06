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
                <img src="<?php echo $gnl->image_not_found_hb($model->profile_photo, 'profile_photo', 1); ?>" />

            </div>
        </div>
        <div class="box-contan"> 
            <div class="box-left"> 
                <h4><?php echo $model->first_name.' '.$model->last_name; ?></h4>
            </div>
            <div class="box-right"> 

                <div class="tr-info">
                    <div class="video_edit">
                    <span class="btnsbox">
                        <a class="edit_vdo" title="Edit" href="<?php echo Url::to(['blog/update', 'id' => \common\components\GeneralComponent::encrypt($model->id)]); ?>"> <i class="fa fa-edit"></i> </a>
                        <a class="delete_vdo" title="Delete" href="<?php echo Url::to(['blog/delete', 'id' => \common\components\GeneralComponent::encrypt($model->id)]); ?>" onclick="return confirm('Are you sure want to delete?')" ><i class="fa fa-remove"></i></a>
</span>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
