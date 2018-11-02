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
?>
<div class="modal-dialog">

    <!-- Modal content-->
     <div class="modal-content">   
         <div class="modal-body-login">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
      <div class="modal-login-left"><img src="<?= Url::base(true) ?>/images/login-left.jpg" /></div>
            <div class="modal-login-right">
            	<h2>Login</h2>
               <!--<p>Lorem ipsum is simply dummytext of the gym industry</p>-->
               <?php
                    $form = ActiveForm::begin([
                                'id' => 'frm-signin',
                                'enableAjaxValidation' => true,
                                'enableClientValidation' => false,
                                'validateOnChange' => false,
                                'validateOnBlur' => false,
                                'options' => ['enctype' => 'multipart/form-data', 'class' => 'registerform','autocomplete'=>'off']
                    ]);
                    ?>
                    
                 <?= $form->field($model, 'email')->label(false)->textInput(['autofocus' => true,'value'=>'','class'=>'form-control','placeholder'=>'Email','autocomplete'=>'off']) ?>
                    
                 <?= $form->field($model, 'password')->label(false)->passwordInput(['value'=>'','class'=>'form-control','placeholder'=>'Password']) ?>
                    <div class="checkbox">
                        <!--<label style="font-size: 14px;">
                            <input type="checkbox"> Remember me
                        </label>-->
                        <span class="forgot-label">
                        <!--<a data-toggle="modal" data-target="#pwdModal" href="javascript:void(0)" data-dismiss="modal" id="forgot-password">Forgot Password</a>-->
                        <a  href="javascript:void(0)" style="font-size: 14px;">Forgot Password</a>
                        </span>
                    </div>
                    <button type="submit" class="btn log-btn">Login</button>
                
                <?php ActiveForm::end(); ?>
            </div>
      </div>
       
    </div>

  </div>

<!--<div class="modal-dialog">
    <div class="modal-content needquestion">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title loing-logo"><img src="<?= Url::base(true) ?>/img/logo.png" alt="FitMakersLive" width="" height=""></h4>
        </div>
        <div class="modal-body">
            <div class="col-md-12 needquestion">
                <div class="row">
                    <?php
                    $form = ActiveForm::begin([
                                'id' => 'frm-signin',
                                'enableAjaxValidation' => true,
                                'enableClientValidation' => false,
                                'validateOnChange' => false,
                                'validateOnBlur' => false,
                                'options' => ['enctype' => 'multipart/form-data', 'class' => 'registerform']
                    ]);
                    ?>

                    <?= $form->field($model, 'email')->textInput(['autofocus' => true,'value'=>$cookie_username]) ?>

                    <?= $form->field($model, 'password')->passwordInput(['value'=>$cookie_pwd]) ?>

													<?=
                                            $form->field($model, 'rememberMe', [
                                                'template' => "<div class=\"checkbox markcheckbox fl loginpage\">\n<label>{input}\n<span class=\"checkbox-material\">
                     	 <span class=\"check\"></span>
                      </span>Remember Me</label>{error}\n{hint}\n</div>",
                                            ])->checkbox([],false)->label(false);
                                            ?>
                    <div class="col-md-12 btnlognin">
                        <div class="col-md-12">
                            <?= Html::submitButton('Log in', ['class' => 'btn btn-primary','style'=>'text-transform:none;']) ?>
                            <p class="footer_privacy_policy">
                                <a data-toggle="modal" onclick="hideOverflowHidden()" data-target="#pwdModal" href="javascript:void(0)" data-dismiss="modal" id="forgot-password">Forgot password? </a>
                            </p>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <div class = "modal-footer">
        </div>
    </div>
</div>-->

