<?php

// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;

$gnl = new \common\components\GeneralComponent();
?>

<div class="col-sm-3"> 
    <div class="box-wrap wow fadeInUp"> 
        <div class="box-img"> 
            <div class="box-img-center"> 
                <img src="<?php echo $gnl->image_not_found_hb($model->blog_image, 'blog_image', 1); ?>" />

            </div>
        </div>
        <div class="box-contan"> 
            <div class="box-left"> 
                <h4><?php echo $model->title; ?></h4>
                <p class="blog_list_date">Date : <?php echo date('d M Y',strtotime($model->created_date));?></p>
                <p class="blog_list_description"><?php echo \common\components\GeneralComponent::wordcutoff($model->description, 100);?></p>
            </div>
            <div class="box-right"> 

                <div class="tr-info">
                    <div class="video_edit">
                    <span class="btnsbox">
                        <a class="edit_vdo" title="Edit" href="<?php echo Url::to(['blog/update', 'id' => \common\components\GeneralComponent::encrypt($model->blog_id)]); ?>"> <i class="fa fa-edit"></i> </a>
                        <a class="delete_vdo" title="Delete" href="<?php echo Url::to(['blog/delete', 'id' => \common\components\GeneralComponent::encrypt($model->blog_id)]); ?>" onclick="return confirm('Are you sure want to delete?')" ><i class="fa fa-remove"></i></a>
</span>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
