<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TrainerVideo */
$gnl = new \common\components\GeneralComponent();
$this->title = $model->title;
$workoutdetail = Yii::$app->db->createCommand("SELECT name FROM workout_type WHERE workout_type_id =" . $model->workout_type_id)->queryOne();

if(\Yii::$app->user->identity->role == 'user')
{
    $lastview = $model->no_of_view+1;
    YII::$app->db->createCommand()->update('trainer_video', ['no_of_view'=>$lastview], 'trainer_video_id = '.$model->trainer_video_id)->execute();
}
?>


<!-- Start Inner Banner area -->
<div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>Play Video</h2>
                    </div>
                </div>
            </div>
 </div>
<!-- End Inner Banner area -->

<div class="online-store-grid padding-space">
            <div class="container">
                <div class="row">
                    <?php echo $this->render('//user/_left_sidebar.php'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-9">
                        <div class="pro-rgt-top">
                            <?php echo $this->render('//user/_user_header.php'); ?>
                        </div>
                        <div class="whatclientsay">
                            <h2 class="section-title-default2 title-bar-high2">Videos</h2>

                            <h3><?php echo $model->title;?></h3>
                            <p>Category : <?php echo @$workoutdetail['name']; ?></p>
                            <p>Description : <?php echo @$model->description; ?></p>
                            <video controls>
                                <source src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].Yii::$app->homeUrl. 'uploads/video_file/'.$model->video_file;?>" type="video/mp4">
                            </video>  
                            <img class="img-circle" style="width:100px;height:100px;" src="<?php echo $gnl->image_not_found_hb($model->user->profile_photo, 'profile_photo',1); ?>" alt="User Avatar">

 <p>Coach Name : <?php echo $model->user->first_name.' '.$model->user->last_name; ?></p>

                        </div>
                    </div>
                </div>
            </div>
</div>

