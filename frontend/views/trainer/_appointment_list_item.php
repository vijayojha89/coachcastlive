<?php 
use yii\helpers\Url;
$gnl = new \common\components\GeneralComponent();?>
<div class="panel panel-default addappointmenttrainerlist">
                                                         <div class="panel-heading"><?php echo $model->user->first_name.' '.$model->user->last_name;?>
                                                             <?php if($model->appointment_status != 2 && $model->status == "1"){ ?> 
                                                             <label><a class="btn btn-primary" href="<?php echo Url::to(['videocall/index','id'=>\common\components\GeneralComponent::encrypt($model->appointment_id)]); ?>"><i class="fa fa-phone"></i>&nbsp;&nbsp;Call</a></label>
                                                             <?php } ?>
                                                         </div> 
                                                          <div class="panel-body">
                                                            <div class="pic-box"> 
                                                                <a> <img class="img-circle" src="<?php echo $gnl->image_not_found_hb($model->user->profile_photo, 'profile_photo', 1); ?>"></a>
                                                            </div> 
                                                            <div class="info-box">
                                                                    <p><strong>Title : </strong><?php echo $model->title;?></p>
                                                                    <p><strong>Reason : </strong><?php echo $model->reason;?></p>
                                                                    <p><strong>Date & Time : </strong><?php echo date('d-M-Y h:i A',strtotime($model->appointment_date));?></p>
                                                                    <p><strong>Fee Pay : </strong>$<?php echo $model->price;?></p>
                                                                    <?php if($model->appointment_status == 0){ ?> 
                                                                    <p>
                                                                        <a href="javascript:void(0);" onclick="action_appointment('<?php echo $model->appointment_id;?>','accept');"><img src="<?php echo Yii::$app->homeUrl;?>images/green-right.png" alt=""> &nbsp;&nbsp;</a>
                                                                        <a href="javascript:void(0);" onclick="action_appointment('<?php echo $model->appointment_id;?>','reject');"><img src="<?php echo Yii::$app->homeUrl;?>images/red-close.png" alt=""></a> 
                                                                        <b style="color:orange;float: right;">Waiting for respond</b>
                                                                    </p>    
                                                                        
                                                                    <?php }else{ ?>
                                                                    <p><strong>Status : </strong> <?php echo ($model->appointment_status == 2)? '<span class="badge badge-danger rejected">Rejected</span>':'<span class="badge badge-success accepted">Accepted</span>';?></p>    
                                                                    <?php } ?>
                                                            </div>
                                                          </div>    
                                                     </div>
