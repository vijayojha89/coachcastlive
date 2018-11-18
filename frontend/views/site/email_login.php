<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\components\GeneralComponent;

/* @var $this yii\web\View */
/* @var $model frontend\models\User */
/* @var $form yii\widgets\ActiveForm */
$cookie_username = $cookie_pwd = '';
$cookies1 = Yii::$app->request->cookies;
if ($cookies1->has('_email')){
    $cookie_username = \Yii::$app->getRequest()->getCookies()->getValue('_email');
}
if ($cookies1->has('_password')){
    $cookie_pwd =  \Yii::$app->getRequest()->getCookies()->getValue('_password');    
}
$this->title = 'Login';
?>
 <!-- Start Inner Banner area -->
 <div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>Login</h2>
                    </div>
                </div>
            </div>
 </div>
<!-- End Inner Banner area -->

<div class="contact-us-area signup loginpage" id="">
            <div class="container">
                <!-- <div class="row">
                <h2 class="section-title-default2 title-bar-high2">Login</h2>    
                </div> -->
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="signupform">
                        <div class="col-lg-6 col-md-6 col-sm-12  col-md-offset-3">
                            <?php
                                $form = ActiveForm::begin([
                                            'id' => 'frm-signin',
                                            'enableAjaxValidation' => true,
                                            'enableClientValidation' => true,
                                            'validateOnChange' => true,
                                            'validateOnBlur' => true,
                                            'options' => ['enctype' => 'multipart/form-data', 'class' => 'registerform','autocomplete'=>'off']
                                ]);
                                ?>

                                <div class="signup">
                                  
                                <?php
                                 echo $form->field($model, 'email', [
                                  'template' => '{label}<i class="fa fa-envelope" aria-hidden="true"></i> {input}{error}{hint}'
                                ])->textInput(['autofocus' => true,'value'=>'','class'=>'form-control','placeholder'=>'Enter your email ID','autocomplete'=>'off']) ;
                                ?>
                                
                                <?=
                                $form->field($model, 'password', [
                                 'template' => '{label}<i class="fa fa-key" aria-hidden="true"></i> {input}{error}{hint}'
                                ])->passwordInput(['value'=>'','class'=>'form-control','placeholder'=>'Enter your Password'])
                                ?>

                                 
                                  <div class="clearfix">
                                    <button type="submit" class="btn-read-more-fill btn-send disabled" style="width:50%;">LOGIN</button>
                                    <a href="<?php echo Url::to(['site/request-password-reset']); ?>" class="" style="text-align:right;width:50%;float:right;padding:15px 0 0 0;">Forgot Password?</a> 
                                  </div>
                                </div>
                                <?php ActiveForm::end(); ?>
                       </div>
                    </div>
                 </div>
                </div>
            </div>
        </div>    