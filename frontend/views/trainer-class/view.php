<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\models\TrainerClass */

$this->title = $model->title;
$gnl = new \common\components\GeneralComponent();
$workoutdetail = Yii::$app->db->createCommand("SELECT name FROM workout_type WHERE workout_type_id =" . $model->workout_type_id)->queryOne();
?>

<!-- Start Inner Banner area -->
<div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>Class Detail</h2>
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
                        <div class="row">
                           <!-- <h2 class="section-title-default2 title-bar-high2">Classe Detail</h2>-->
                           <div class="col-md-2"><p><img class="img-circle" style="width:100px;height:100px;" src="<?php echo $gnl->image_not_found_hb($model->user->profile_photo, 'profile_photo',1); ?>" alt="User Avatar"></p></div>
                           <div class="col-md-8 traierclass">
                            <h3>Title : <?= Html::encode($this->title) ?></h3>
                            <h3>Category : <?= $workoutdetail['name'] ?></h3>
                            <h3>Start Date : <?= $model->start_date ?></h3>
                            <h3>End Date : <?= $model->end_date ?></h3>
                            <h3>Time : <?= $model->time ?></h3>
                            <h3>Price : $<?= $model->price ?></h3>
                            <h3>Coach Name : <?php echo $model->user->first_name.' '.$model->user->last_name; ?></h3>
                            

                            
                            <p><?php echo $model->description;?></p>

                            </div>
                            <div class="col-md-2">
                            <a class="btn choosebtn" title="Join"  href="<?php echo Url::to(['trainer-class/join', 'id' => \common\components\GeneralComponent::encrypt($model->trainer_class_id)]); ?>">Join Now</a>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</div>