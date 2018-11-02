<?php 
use yii\helpers\Url;
$gnl = new \common\components\GeneralComponent(); ?>
<div class="panel panel-default addappointmenttrainerlist">
    <div class="panel-heading"><?php echo $model->user->first_name . ' ' . $model->user->last_name; ?>
        <?php if($model->appointment_status == 0) { ?>
        <label><a class="btn reject_btn" href="javascript:void(0);" onclick="cancelAppointment(<?php echo $model->appointment_id;?>);">Cancel</a></label>
        <?php } ?>
        
        <?php if($model->appointment_status == "1"){ ?> 
                                                             <label><a class="btn btn-primary" href="<?php  echo Url::to(['videocall/index']);?>"><i class="fa fa-phone"></i>&nbsp;&nbsp;Call</a></label>
                                                             <?php } ?>
                                                             
    </div> 
    <div class="panel-body">
        <div class="pic-box"> 
            <a><img class="img-circle" src="<?php echo $gnl->image_not_found_hb($model->user->profile_photo, 'profile_photo', 1); ?>"></a>
        </div> 
        <div class="info-box">
            <p><strong>Title : </strong><?php echo $model->title; ?></p>
            <p><strong>Reason : </strong><?php echo $model->reason; ?></p>
            <p><strong>Date & Time : </strong><?php echo date('d M Y h:i A',strtotime($model->appointment_date)); ?></p>
            <p><strong>Fee Pay : </strong>$<?php echo $model->price; ?></p>
            <?php if($model->status == 2) { ?>
            <p><strong>Status : </strong><span class="badge badge-danger">Cancelled</span></p>    
            <?php } ?> 
            <?php if($model->status != 2) { ?>
            <p><strong>Status : </strong><?php echo ($model->appointment_status == 0) ? '<span class="badge badge-danger">Pending</span>' : '<span class="badge badge-success">Accepted</span>'; ?></p>    
            <?php } ?> 
        </div>
    </div>    
</div>
