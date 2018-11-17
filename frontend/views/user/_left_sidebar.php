<?php
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */

$gnl = new common\components\GeneralComponent();
$userdata = Yii::$app->db->createCommand("Select * from user where id = ".Yii::$app->user->id)->queryOne();
?>


    <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="right-sidebar">

                            <div class="profile-pic">
                                <div class="userpic"><a class="imguser" href="#"><img src="<?php echo $gnl->image_not_found_hb( $userdata['profile_photo'],'profile_photo',1);?>" alt="profile"></a><a
                                        class="editpic" href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a></div>
                                <span><?php  echo $userdata['first_name'].' '.$userdata['last_name'];?></span>
                            </div>

                            <div class="availa">
                                <a class="fl choosebtn" href="<?php echo Url::to(['trainer/profile']); ?>"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;&nbsp;   Edit Profile</a>
                                <a class="fl choosebtn" href="<?php echo Url::to(['trainer/availability']); ?>"><i class="fa fa-calendar" aria-hidden="true"></i> &nbsp;&nbsp;Availability</a>
                                <!--<a class="fr choosebtn" href="#">My Schedule</a>-->
                                <a class="fr choosebtn" href="<?php echo Url::to(['blog/index']); ?>"><i class="fa fa-book" aria-hidden="true"></i> &nbsp;&nbsp;My Blogs</a>
                            </div>

                            <div class="msg-check">
                                <a class="fl" href="<?php echo Url::to(['message/index']); ?>"><i class="fa fa-commenting-o" aria-hidden="true"></i><span>Messages <span class="badge btn-danger">2</span></span></a>
                                <a class="fl" href="javascript:void(0);" style="cursor:default;"><i class="fa fa-user" aria-hidden="true" style="color:#8cc63f;"></i><span style="color:#8cc63f;">Online</span></a>
                            </div>


                            <div class="single-sidebar sidebar-last">
                                <div class="join-us">
                                    <img src="<?php echo YII::$app->homeUrl;?>img/join-us.jpg" alt="">
                                    <div class="join-content">
                                        <!-- <div class="percent"><span>25%</span>off</div> -->
                                        <p>Section for Videos or images that Site Administrator uploads from Backend
                                            for advertisement, etc</p>
                                        <a class="custom-button" href="javascript:void(0);" data-title="Join Us Now!">Join Us Now!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


<!--<section class="profile-banner">    
    <div class="user-header">
        <div class="user-profile-img-box">
                    <div class="profile-img-center">
                    	<span>
                    	<img src="" alt="Profile" width="" height="">
                        </span>
                        </div>
                    </div> 
        <h1></h1>
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
                    
     	
</section>-->
