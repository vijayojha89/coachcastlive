<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
//echo "<pre>";
//print_r(Yii::$app->user->identity);

//print_r(Yii::$app->params['userrole']);
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

      


    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    
    <?php if(\Yii::$app->user->identity->role == "subAdmin")
            {if ($model->isNewRecord) { ?>
        <?= $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'autocomplete' => false]) ?>
    <?php } else { ?>
        <?= $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'autocomplete' => false, "disabled" => "disabled"]) ?>
            <?php } }?>
    
    <?php if(\Yii::$app->user->identity->role == "superAdmin")
            { ?>
        <?= $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'autocomplete' => false]) ?>
    <?php  }?>
    <?php if ($model->isNewRecord) { ?>
        <?= $form->field($model, 'password_hash')->passwordInput(['autocomplete' => false]) ?>

        <?= $form->field($model, 'repeat_password')->passwordInput(['autocomplete' => false]) ?>
    <?php } ?>

<div class="cl"></div>
   <div class="image_setting">
        <div class="upload_logo_file">
            <i class="glyphicon glyphicon-open-file"></i>
            <p>File Upload</p>
        </div><?php $gnl = new \common\components\GeneralComponent(); ?><?= $form->field($model, 'profile_photo')->fileInput() ?><img id="preview" src="<?php echo $gnl->image_not_found_profile('profile_photo', $model->profile_photo); ?>" height="100" />
    </div>
<div class="cl"></div><br />
    <div class="form-group" id="button_update_delete">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>&nbsp;&nbsp;&nbsp;
        <?= Html::a(Yii::t('app', 'Cancel'), ['/'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
//    (function() {

    var URL = window.URL || window.webkitURL;

    var input = document.querySelector('#user-profile_photo');
    var preview = document.querySelector('#preview');
    
    // When the file input changes, create a object URL around the file.
    input.addEventListener('change', function () {
        preview.src = URL.createObjectURL(this.files[0]);
    });
    
    // When the image loads, release object URL
    preview.addEventListener('load', function () {
        URL.revokeObjectURL(this.src);
        
       // alert('jQuery code here. W: ' + this.width + ', H: ' + this.height);
    });
</script>
