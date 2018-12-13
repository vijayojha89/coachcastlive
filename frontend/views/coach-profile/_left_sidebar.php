<?php
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */

$gnl = new common\components\GeneralComponent();

?>


    <div class="col-lg-3 col-md-3 col-sm-3">
                        <div class="right-sidebar">

                            <div class="profile-pic">
                                <div><img src="<?php echo $gnl->image_not_found_hb($model->profile_photo, 'profile_photo', 1); ?>" alt="profile">
                                <span><?php echo $model->first_name. ' '.$model->last_name; ?></span>
                                </div>
                            </div>    


                            <div class="availa">
                                <a class="fl choosebtn" href="<?php echo Url::to(['coach-profile/view', 'id' => \common\components\GeneralComponent::encrypt($model->id)]); ?>"><i class="fa fa-user" aria-hidden="true"></i> &nbsp;&nbsp;My Profile</a>
                                <a class="fl choosebtn" href="<?php echo Url::to(['coach-profile/availability', 'id' => \common\components\GeneralComponent::encrypt($model->id)]); ?>"><i class="fa fa-calendar" aria-hidden="true"></i> &nbsp;&nbsp;Availability</a>
                                <!--<a class="fr choosebtn" href="#">My Schedule</a>-->
                                <a class="fr choosebtn" href="<?php echo Url::to(['coach-profile/aboutme', 'id' => \common\components\GeneralComponent::encrypt($model->id)]); ?>"><i class="fa fa-user" aria-hidden="true"></i> &nbsp;&nbsp;About Me</a>
                                <a class="fr choosebtn" href="<?php echo Url::to(['coach-profile/book-appointment','id' => \common\components\GeneralComponent::encrypt($model->id)]); ?>"><i class="fa fa-clock-o" aria-hidden="true"></i> &nbsp;&nbsp;Book Appointment</a>
                                <a class="fr choosebtn" href="<?php echo Url::to(['message/sendmessage','id'=>$model->id]);?>" data-toggle="modal" data-target="#message-modal" data-pjax="false"><i class="fa fa-envelope" aria-hidden="true"></i> &nbsp;&nbsp;Send Message</a>
                            </div>

                            <div class="msg-check">
                                <a class="fl" href="javascript:void(0);" style="cursor:default;"><i class="fa fa-user" aria-hidden="true" style="color:#8cc63f;"></i><span style="color:#8cc63f;">Online</span></a>
                            </div>


                            <div class="single-sidebar sidebar-last">
                                <div class="join-us">
                                    <img src="<?php echo YII::$app->homeUrl; ?>img/join-us.jpg" alt="">
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

          


<div id="message-modal" class="modal fade filterpopup" role="dialog">
  <div class="modal-dialog" role="document" style="margin-top: 100px;">
    <div class="modal-content">
      
      
    </div>

  </div>
</div>