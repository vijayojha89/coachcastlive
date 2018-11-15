
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\components\MasterComponent;
use common\components\GeneralComponent;
$gnl = new GeneralComponent();
 
                    $today = date_create(date('Y-m-d H:i:s'));
                    $comment_date = date_create($model['created_date']);
                    $time_diff = date_diff($today,$comment_date);
                    if($time_diff->d == 0 && $time_diff->h == 0 && $time_diff->i == 0)
                    {$display_comment_time = $time_diff->s." seconds ago";}
                    else if($time_diff->d == 0 && $time_diff->h == 0 && $time_diff->i != 0)
                    {$display_comment_time = $time_diff->i." minutes ago";}
                    else if($time_diff->d == 0 && $time_diff->h != 0 && $time_diff->h == 1)
                    {$display_comment_time = "About an hour ago";}
                    else if($time_diff->d == 0 && $time_diff->h > 2)
                    {$display_comment_time = $time_diff->h." hours ago";}
                    else if($time_diff->d != 0 && $time_diff->d == 1)
                    {$display_comment_time = "Yesterday at ".date("H:i",strtotime($model['created_date']));}
                    else 
                    {$display_comment_time =  common\components\GeneralComponent::front_date_format($model['created_date']);}
                    ?>


                    <div class="single-client-say" id="notifilistdiv_<?php echo $model['notification_generalization_id']; ?>">
                                    <div class="pull-left client-picture">
                                        <img src="<?php echo $gnl->image_not_found_api_thumb('profile_photo', $model['notification_from_profile_photo']) ?>">
                                    </div>
                                    <div class="media-body client-content">
                                        <h3><?=$model['notification_from_name'];?></h3>
                                        <p><?php echo $model['notification_text']; ?></p>
                                        <p><i class="fa fa-clock-o"></i> <?= $display_comment_time ?></p>
                                    </div>
                                </div>

<?php
$this->registerJs(
        '

            
            ', \yii\web\VIEW::POS_HEAD);
?>