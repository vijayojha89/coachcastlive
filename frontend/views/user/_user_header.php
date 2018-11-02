<?php
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */

$gnl = new common\components\GeneralComponent();
$userdata = Yii::$app->db->createCommand("Select * from user where id = ".Yii::$app->user->id)->queryOne();
?>


<section class="profile-banner">    
    <div class="user-header">
        <div class="user-profile-img-box">
                    <div class="profile-img-center">
                    	<span>
                    	<img src="<?php echo $gnl->image_not_found_hb( $userdata['profile_photo'],'profile_photo',1);?>" alt="Profile" width="" height="">
                        </span>
                        </div>
                    </div> 
        <h1><?php  echo $userdata['first_name'].' '.$userdata['last_name'];?></h1>
    </div>
</section>

<section>
 	 		<div class="user-pro-tabs pro-tabs">
                        	<ul>
                               <li class="<?php if (Yii::$app->controller->action->id == 'myappointment') { ?>active<?php } ?>"><a href="<?php echo Url::to(['user/myappointment']); ?>"><i class="fa fa-clock-o"></i>My Appointment</a></li>     
                            <li class="<?php if (Yii::$app->controller->id == 'trainer-video' AND Yii::$app->controller->action->id != 'transations' AND Yii::$app->controller->action->id != 'schedule') { ?>active<?php } ?>"><a href="<?php echo Url::to(['trainer-video/index']); ?>"><i class="fa fa-video-camera"></i>Videos</a></li>
                            <li class="<?php if (Yii::$app->controller->id == 'trainer-class') { ?>active<?php } ?>"><a href="<?php echo Url::to(['trainer-class/index']); ?>"><i class="fa fa-universal-access" aria-hidden="true"></i>Classes</a></li>
                            <li class="<?php if (Yii::$app->controller->action->id == 'schedule') { ?>active<?php } ?>"><a href="<?php echo Url::to(['trainer-video/schedule']); ?>"><i class="fa fa-user"></i>Find Coach</a></li>
                            <li class="<?php if (Yii::$app->controller->action->id == 'transations') { ?>active<?php } ?>"><a href="<?php echo Url::to(['trainer-video/transations']); ?>"><i class="fa fa-money"></i>Transactions</a></li>
                            <li class="<?php if (Yii::$app->controller->action->id == 'profile') { ?>active<?php } ?>"><a href="<?php echo Url::to(['trainer/profile']); ?>"><i class="fa fa-id-card"></i>Profile</a></li>
                            </ul>
                        </div> 	
                    
     	
</section>
<?php //echo $userdata['first_name'].' '.$userdata['last_name'];?>