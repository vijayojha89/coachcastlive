<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\time\TimePicker;
use dosamigos\tinymce\TinyMce;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model frontend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bio')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password_hash')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password_reset_token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile_no')->textInput(['maxlength' => true]) ?>


    <div class="cl"></div>
    <div class="image_setting">
        <div class="upload_logo_file">
            <i class="glyphicon glyphicon-open-file"></i>
            <p>File Upload</p>
        </div><?php $gnl = new \common\components\GeneralComponent(); ?><?= $form->field($model, 'profile_photo')->fileInput() ?><img id="preview_profile_photo_0" src="<?php echo $gnl->image_not_found_hb($model->profile_photo, 'profile_photo'); ?>" /></div><div class="cl"></div>    <?= $form->field($model, 'role')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'qualification_id')->textInput() ?>

    <?= $form->field($model, 'subject_ids')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cv_doc')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'expertise_ids')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'referral_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'social_type')->textInput() ?>

    <?= $form->field($model, 'social_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile_verified')->textInput() ?>

    <?= $form->field($model, 'university')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_questions')->textInput() ?>

    <?= $form->field($model, 'email_verified')->textInput() ?>

    <?= $form->field($model, 'user_last_login')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group" id="button_update_delete">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['user/index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    var URL = window.URL || window.webkitURL;

    var input_0 = document.querySelector('#user-profile_photo');
    var preview_0 = document.querySelector('#preview_profile_photo_0');

    input_0.addEventListener('change', function () {
        preview_0.src = URL.createObjectURL(this.files[0]);
    });

    preview_0.addEventListener('load', function () {
        URL.revokeObjectURL(this.src);

    });
</script>