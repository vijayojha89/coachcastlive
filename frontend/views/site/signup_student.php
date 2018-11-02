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
<!-- Modal -->
<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content needquestion">
        <div class="modal-header">
            <button type="button" onclick="myFunctionClose()" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title loing-logo"><img src="img/logo.png" alt="FitMakersLive" width="" height=""></h4>
            <h2>Create an account to start getting answers to your questions</h2>
        </div>
	       
        <div class="modal-body mCustomScrollbar">
     	   <div class="col-md-12 needquestion">
                <div class="row">                    
                    <?php
                    $form = ActiveForm::begin([
																					'id' => 'frm-signup-student',
																					'enableAjaxValidation' => true,
																					'options' => ['enctype' => 'multipart/form-data', 'class' => 'registerform']
                    ]);
                    ?>
                    <?= $form->field($model, 'role')->hiddenInput(['maxlength' => true, 'value' => 'student'])->label(false) ?>
                    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'qualification_id')->textInput() ?>
                    <?= $form->field($model, 'subject_ids')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'referral_code')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'password_hash')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'confirm_password')->textInput(['maxlength' => true]) ?>
                    <div class="col-md-12 btnlognin">
                        <div class="row">
                            <?= Html::submitButton('sign up', ['class' => 'btn btn-primary']) ?>
                            <p class="footer_privacy_policy">
                                By signing up you agree to our <a href="javascript:void(0)">Terms and Conditions</a> and <a href="javascript:void(0)">Privacy Policy</a>
                            </p>  
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
	       <div class="cl"></div>
        <div class="modal-footer">
            <p class="footer_signin">
                Already have an account ?<br />
                <a href="javascript:void(0)">Log in to continue</a>
            </p>
            <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
        </div>
        <div class="cl"></div>
    </div>
</div>