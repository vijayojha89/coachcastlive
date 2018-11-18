<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\components\GeneralComponent;
use yii\helpers\Url;
$this->title = "Sign Up";
/* @var $this yii\web\View */
/* @var $model frontend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<!-- Start Inner Banner area -->
<div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>Sign Up</h2>
                    </div>
                </div>
            </div>
 </div>
<!-- End Inner Banner area -->


<div class="contact-us-area signup" id="">
            <div class="container">
                <!-- <div class="row">
                <h2 class="section-title-default2 title-bar-high2">Sign Up</h2>    
                </div> -->
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="signupform">
                        <div class="col-lg-6 col-md-6 col-sm-12  col-md-offset-3">
                        <?php
                        if(empty($model->user_type))
                        {
                            $model->user_type = 1;
                        }
                                $form = ActiveForm::begin([
                                            'id' => 'frm-signup-tutor',
                                            'enableAjaxValidation' => true,
                                            'enableClientValidation' => true,
                                            'options' => ['enctype' => 'multipart/form-data', 'class' => 'registerform']
                                ]);
                                ?>


                                <div class="signup">
                                  
                                <?= $form->field($model, 'user_type')->label("Select User Role")->radioList(array('1'=>'User',2=>'Coach')); ?>
                                <?= $form->field($model, 'social_type')->hiddenInput()->label(false) ?>
                                <?= $form->field($model, 'social_id')->hiddenInput()->label(false) ?>

                                


                                <?php
                                echo $form->field($model, 'first_name', [
                                    'template' => '{label}<i class="fa fa-user" aria-hidden="true"></i> {input}{error}{hint}'
                                    ])->textInput(['placeholder'=>'Ente yourr first name',]) ;
                                    


                                echo $form->field($model, 'last_name', [
                                    'template' => '{label}<i class="fa fa-user" aria-hidden="true"></i> {input}{error}{hint}'
                                    ])->textInput(['placeholder'=>'Enter your last name',]) ;

                                echo $form->field($model, 'mobile_no', [
                                    'template' => '{label}<i class="fa fa-phone" aria-hidden="true"></i> {input}{error}{hint}'
                                    ])->textInput(['placeholder'=>'Enter your mobile Number',]) ;

                                echo $form->field($model, 'email', [
                                    'template' => '{label}<i class="fa fa-envelope" aria-hidden="true"></i> {input}{error}{hint}'
                                    ])->textInput(['placeholder'=>'Enter your email ID',]) ;
                                ?>

                                    
                                    <?=
                                    $form->field($model, 'password_hash', [
                                        'template' => '{label}<i class="fa fa-key" aria-hidden="true"></i> {input}{error}{hint}'
                                    ])->passwordInput(['placeholder'=>'Enter your password',])
                                    ?>

                                    <?=
                                    $form->field($model, 'confirm_password', [
                                        'template' => '{label}<i class="fa fa-key" aria-hidden="true"></i> {input}{error}{hint}'
                                    ])->passwordInput(['placeholder'=>'Enter your confirm password',])
                                    ?>

                                  <div class="clearfix">
                                        <div class="signup-bottom"> 
                                            <?php echo Html::submitButton('Sign Up', ['class' => 'btn-read-more-fill btn-send disabled']) ?>
                                        </div>
                                  </div>
                                  
                                  <p class="footer_signin">
                                        Already have an account ?<br />
                                        <a href="<?php echo Url::to(['/site/email-login']); ?>" id="btn_email_signin">Log in to continue</a>
                                    </p>

                                </div>
                                <div class="modal-footer">

                                 <p class="footer_privacy_policy">
                                    By signing up you agree to our <a href="<?php echo Url::to(['/site/terms']); ?>" target="_blank">Terms and Conditions</a> and <a href="<?php echo Url::to(['/site/privacy']); ?>" target="_blank">Privacy Policy</a>
                                </p>
                                   
                                </div>
                                <?php ActiveForm::end(); ?>
                       </div>
                    </div>
                 </div>
                </div>
            </div>
        </div>    
