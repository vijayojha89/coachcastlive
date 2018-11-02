<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
                    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'mobile_no')->textInput(['maxlength' => true]) ?>
                   
                    <?php if ($model->isNewRecord) { ?>
                    <?= $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'autocomplete' => false]) ?>
                    <?php } else { ?>
                    <?= $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'autocomplete' => false, "disabled" => "disabled"]) ?>
                    <?php } ?>  

                    <?php
                    echo $form->field($model, 'qualification_id')->label('Choose Qualification')
                            ->dropDownList(ArrayHelper::map(\common\models\Qualification::find()
                                            ->where(['status' => 1])->asArray()->all(), 'qualification_id', 'name'), ['prompt' => 'Select Qualification']);
                    ?>

                    <?php
                    $model->subject_ids = $modelSubjectSelected;
                    echo $form->field($model, 'subject_ids')
                            ->label('Choose Subject')
                            ->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(common\models\Subject::find()->where(['status' => 1])
                                            ->asArray()->all(), 'subject_id', 'name'),
                                'options' => ['multiple' => true, 'placeholder' => 'Select Subject'],
                    ]);
                    ?>  

                    <?php if ($model->isNewRecord) { ?>
                    <?= $form->field($model, 'referral_code', ['enableAjaxValidation' => true])->textInput() ?>
                    <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'confirm_password')->passwordInput(['maxlength' => true]) ?>
                    <?php } ?>
    <?php



if($model->mobile_verified == 1)
{ ?>
     <div class="form-group field-user-mobile_verified">
                        <input name="User[mobile_verified]" value="1" type="hidden">
                        <label><input type="checkbox" checked disabled="disabled"> Mobile Verified</label>
                    </div>
    
<?php }else{ 
    
    echo $form->field($model, 'mobile_verified')->checkbox();
    
}
?>

 <?php  if($model->email_verified == 1){ ?>

     <div class="form-group field-user-email_verified">
                        <input name="User[email_verified]" value="1" type="hidden">
                        <label><input type="checkbox" checked disabled="disabled"> Email Verified</label>
                    </div>
    
 <?php }else{
         
                  echo $form->field($model, 'email_verified')->checkbox();
    
    } ?>
     <?= $form->field($model, 'user_type')->hiddenInput(['maxlength' => true])->label(false) ?>
    
    
    <div class="form-group" id="button_update_delete">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>&nbsp;&nbsp;&nbsp;
        <?php echo Html::a(Yii::t('app', 'Cancel'), ['user/student'], ['class' => 'btn btn-danger']);
        ?>
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
