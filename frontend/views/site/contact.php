<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;


$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;
?>

<!--<div class="cmsbanner">
    <img src="<?php echo Yii::$app->homeUrl;?>uploads/header_image/cvUj7Aaybry_X-27DRkMg744SQWNAw2Y.jpg" class="img-responsive" alt="">
</div>-->
 <!-- Start Inner Banner area -->
 <div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>Contact Us</h2>
                    </div>
                </div>
            </div>
        </div>
<!-- End Inner Banner area -->

<div class="padding-space">
    <div class="container">
            <div class="row">
                <h2 class="section-title-default2 title-bar-high2">Contact Us</h2> 
            </div>
            <div class="row">
                If you have business inquiries or other questions, please fill out the following form to contact us.
            </div>
            <div class="row padding-space">
                <div class="col-md-6 col-sm-12">
                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                        
                        <?= $form->field($model, 'name')->textInput() ?>

                        <?= $form->field($model, 'email') ?>

                        <?= $form->field($model, 'subject') ?>

                        <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

                     
                         <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-md-3">{image}</div><div class="col-md-9">{input}</div></div>',
                ]) ?>



                        <div class="form-group" style="text-align: right;">
                            <?= Html::submitButton('Submit', ['class' => 'btn', 'name' => 'contact-button']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                </div>
                <div class="col-md-6 col-sm-12">
                <div class="map-bg">
                      <img src="<?php echo Yii::$app->homeUrl;?>images/map-bg.png" class="img-responsive" alt="">
                      </div>
                      <div class="padding-space">
                          <p><label>Address : </label>Washington State Office, 1102 A St #438, Tacoma, WA 98402	</p>
                          <p><label>Email : </label>info@coachcastlive.com</p>
                          <p><label>Phone : </label>(253) 617-3805</p>
                      </div>
                </div>
            </div>

    </div>
</div>
