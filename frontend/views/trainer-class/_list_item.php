<?php

// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;

$gnl = new \common\components\GeneralComponent();
$workoutdetail = Yii::$app->db->createCommand("SELECT name FROM workout_type WHERE workout_type_id =" . $model->workout_type_id)->queryOne();
?>
<?php //echo Url::to(['user/profile','id'=>$model->id]); ?>
<div class="col-sm-3"> 
    <div class="box-wrap wow fadeInUp"> 
        <div class="box-img"> 
            <div class="box-img-center"> 
                <img src="<?php echo $gnl->image_not_found_hb($model->class_image, 'class_image',1); ?>" />

            </div>
        </div>
        <div class="box-contan"> 
            <div class="box-left"> 
                <h4><?php echo $model->title; ?></h4>
                <p class="blog_list_date">Category : <?php echo @$workoutdetail['name']; ?></p>
                <p class="blog_list_date">Start Date : <?php echo date('d M Y',strtotime($model->start_date));?></p>
                <p class="blog_list_date">End Date : <?php echo date('d M Y',strtotime($model->end_date));?></p>
                <p class="blog_list_date">Time : <?php echo date('h:i A',strtotime($model->time));?></p>
                <!--<p class="blog_list_description">theq ucik brown fox jumps right over the laozy dog.theq ucik brown fox jumps right over the laozy dog.theq ucik brown fox jumps right over the laozy dog.theq ucik brown fox jumps right over the laozy dog...</p>-->
                
            </div>
            <div class="box-right"> 

                <div class="tr-info">
                    <div class="video_edit class_prize">
                        <h4>Price <span> $<?php echo $model->price; ?></span></h4>
                        <span class="btnsbox">
                        <a class="edit_vdo" title="Edit" href="<?php echo Url::to(['trainer-class/update', 'id' => \common\components\GeneralComponent::encrypt($model->trainer_class_id)]); ?>"> <i class="fa fa-edit"></i> </a>
                        <a class="delete_vdo" title="Delete" onclick="return confirm('Are you sure want to delete?')" href="<?php echo Url::to(['trainer-class/delete', 'id' => \common\components\GeneralComponent::encrypt($model->trainer_class_id)]); ?>"><i class="fa fa-remove"></i></a>
                        <span>
                    </div>
                    

                </div>
            </div>
        </div>
    </div>
</div>
