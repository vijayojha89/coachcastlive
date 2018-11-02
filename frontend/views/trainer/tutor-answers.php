<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php
                    $form = ActiveForm::begin(['id' => 'form-select-answer',
                                'enableAjaxValidation' => true,
                                'enableClientValidation' => true,
                                'options' => ['enctype' => 'multipart/form-data'],
                    ]);
                    ?>
<div class="checkbox markcheckbox">
<div class="form-group field-chat-chat_id">
<input type="hidden" name="Chat[chat_id]" value="">
<div id="chat-chat_id">
<?php foreach ($chat_data as $chat) { ?>
<li class="right">
    <div class="checkbox markcheckbox fr">
        
        <div id="reportabuse-option_id">
            <div class="radio radio-primary radio_payment">
        
                <input type="radio"   id="option_id_<?=$chat['chat_id']?>" name="chat_id" value="<?=$chat['chat_id']?>">
               <label for="option_id_<?=$chat['chat_id']?>">
                                <div class="user-info">
                                    <div class="pic-box">
                                        <img class="online" alt="user image" src="<?=$chat['tutor_profile_photo_thumb']?>">
                                    </div>
                                    <div class="info-box">
                
                
                                    <?php if($chat['message_type'] == 0)
                                    { ?>
                                        <div class="chatingtext"><?= $chat['message'] ?></div>
                                    <?php } ?>
                                    
                                    <?php
                                    if($chat['message_type'] == 1)
                                    { 
                                     ?> 
                                    <div class="chatingtext">
                                                      <a>
                                                         <img src="<?=\common\components\GeneralComponent::get_file_type($chat['file_name_original'])?>" alt="">
                                                          <?= $chat['file_name_original']?>   
                                                       </a>    
                                                </div>
                                   <?php  }    ?>


                                    <div class="chatingtime"><?=date('H:i',$chat['created_date'])?></div>    
                                    </div>
                                </div> 
                   </label></div>
    </div>
                   </li>
<?php } ?>
</div>
<div class="help-block"></div>
</div>        </div>

               
                    
               <?php  ActiveForm::end(); ?>          
                    
 
                      