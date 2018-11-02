<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Setting */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    form .orifinal_img .image_setting img{float: left!important;
    height: auto!important;
    margin: -12px 0px 0px 180px!important;
    padding: 0!important;
    width: auto!important;}
@media (max-width:480px) {
    form .orifinal_img .image_setting img {
    float: left!important;
    height: auto!important;
    margin: 70px 0px 25px 0px!important;
    padding: 0!important;
    width: auto!important;
}
}
</style>

<div class="setting-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?php //  $form->field($model, 'setting_id')->textInput() ?>

 
<div class="cl"></div>
<div class="orifinal_img">
<div class="image_setting">
        <div class="upload_logo_file">
            <i class="glyphicon glyphicon-open-file"></i>
            <p>File Upload</p>
        </div><?php $gnl = new \common\components\GeneralComponent(); ?><?= $form->field($model, 'setting_logo_image')->fileInput() ?>
     <img id="preview_logo" src="<?php echo $gnl->image_not_found_hb_o( $model->setting_logo_image,'setting_logo_image'); ?>" />
    </div>

<div class="cl"></div>
<div class="image_setting">
        <div class="upload_logo_file">
            <i class="glyphicon glyphicon-open-file"></i>
            <p>File Upload</p>
        </div><?= $form->field($model, 'setting_favicon_image')->fileInput() ?>
    <img id="preview_fevi" src="<?php echo $gnl->image_not_found_hb_o( $model->setting_favicon_image,'setting_favicon_image'); ?>" />
    </div>   
</div>
<div class="cl"></div>
<?= $form->field($model, 'stripe_payment')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'manage_commission')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'referral_commission')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'chat_session_timeout')->textInput(['maxlength' => true])->label('Chat Session Timeout (Hours)') ?>
<?= $form->field($model, 'student_accept_answer_timeout')->textInput(['maxlength' => true])->label('Student Accept Answer Timeout (Hours)') ?>

<h4>Social Links</h4>
<?= $form->field($model, 'setting_facebook')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'setting_instagram')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'setting_twitter')->textInput(['maxlength' => true]) ?>

<div class="cl"></div>
<h4>Images for Refer Screen</h4>

<div class="cl"></div>
<div class="image_setting">
        <div class="upload_logo_file">
            <i class="glyphicon glyphicon-open-file"></i>
            <p>File Upload</p>
        </div><?php $gnl = new \common\components\GeneralComponent(); ?>
        <?= $form->field($model, 'refer_step1')->fileInput() ?>
    <img id="preview_step1" src="<?php echo $gnl->image_not_found_hb( $model->refer_step1,'refer_step1'); ?>" height="100" />
</div>

<div class="cl"></div>
<div class="image_setting">
        <div class="upload_logo_file">
            <i class="glyphicon glyphicon-open-file"></i>
            <p>File Upload</p>
        </div><?php $gnl = new \common\components\GeneralComponent(); ?>
        <?= $form->field($model, 'refer_step2')->fileInput() ?>
    <img id="preview_step2" src="<?php echo $gnl->image_not_found_hb( $model->refer_step2,'refer_step2'); ?>" height="100" />
</div>

<div class="cl"></div>
<div class="image_setting">
        <div class="upload_logo_file">
            <i class="glyphicon glyphicon-open-file"></i>
            <p>File Upload</p>
        </div><?php $gnl = new \common\components\GeneralComponent(); ?>
        <?= $form->field($model, 'refer_step3')->fileInput() ?>
    <img id="preview_step3" src="<?php echo $gnl->image_not_found_hb( $model->refer_step3,'refer_step3'); ?>" height="100" />
</div>

<?php if($model->is_push_notification == 1){?>
    <div id="pushnotification_div" >
     <div class="cl"></div><br />
     <?= $form->field($model, 'push_notification_android')->textInput();?>
     <div class="cl"></div><br />
     <?= $form->field($model, 'push_notification_ios_password')->textInput();?>
     <div class="cl"></div><br />
     <div class="image_setting">
        <div class="upload_logo_file">
            <i class="glyphicon glyphicon-open-file"></i>
            <p>File Upload</p>
        </div><?= $form->field($model, 'push_notification_ios')->fileInput() ?>
    </div> 
    </div>   
<?php }?>

<?php if($model->is_s3_image_upload == 1){?>
    <div id="s3_div" >
     <div class="cl"></div><br />
     <?= $form->field($model, 's3_region')->textInput();?>
     <div class="cl"></div><br />
     <?= $form->field($model, 's3_key')->textInput();?>
     <div class="cl"></div><br />
     <?= $form->field($model, 's3_secret')->textInput();?>
     <div class="cl"></div><br />
     <?= $form->field($model, 's3_defaultBucket')->textInput();?>
     <div class="cl"></div><br />
    </div>   
<?php }?>
				<div class="form-group" id="button_update_delete">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>&nbsp;&nbsp;&nbsp;

    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>

    var URL = window.URL || window.webkitURL;

    var input_logo = document.querySelector('#setting-setting_logo_image');
    var preview_logo = document.querySelector('#preview_logo');
    
    input_logo.addEventListener('change', function () {
        preview_logo.src = URL.createObjectURL(this.files[0]);
    });
    
    preview_logo.addEventListener('load', function () {
        URL.revokeObjectURL(this.src);
    
    });
</script>
<script>

    var URL = window.URL || window.webkitURL;

    var input = document.querySelector('#setting-setting_favicon_image');
    var preview = document.querySelector('#preview_fevi');

    input.addEventListener('change', function () {
        preview.src = URL.createObjectURL(this.files[0]);
    });
 
    preview.addEventListener('load', function () {
        URL.revokeObjectURL(this.src);
  
    });
</script>
<script>
                    var URL = window.URL || window.webkitURL;

                    var input_0 = document.querySelector('#setting-refer_step1');
                    var preview_0 = document.querySelector('#preview_step1');

                    input_0.addEventListener('change', function () {
                        preview_0.src = URL.createObjectURL(this.files[0]);
                    });

                    preview_0.addEventListener('load', function () {
                        URL.revokeObjectURL(this.src);

                    });
                    </script><script>
                    var URL = window.URL || window.webkitURL;

                    var input_1 = document.querySelector('#setting-refer_step2');
                    var preview_1 = document.querySelector('#preview_step2');

                    input_1.addEventListener('change', function () {
                        preview_1.src = URL.createObjectURL(this.files[0]);
                    });

                    preview_1.addEventListener('load', function () {
                        URL.revokeObjectURL(this.src);

                    });
                    </script><script>
                    var URL = window.URL || window.webkitURL;

                    var input_2 = document.querySelector('#setting-refer_step3');
                    var preview_2 = document.querySelector('#preview_step3');

                    input_2.addEventListener('change', function () {
                        preview_2.src = URL.createObjectURL(this.files[0]);
                    });

                    preview_2.addEventListener('load', function () {
                        URL.revokeObjectURL(this.src);

                    });
                    </script>