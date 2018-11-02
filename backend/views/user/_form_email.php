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

      
        <?= $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'autocomplete' => false]) ?>
   
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
