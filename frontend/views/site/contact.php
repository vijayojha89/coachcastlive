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

<div class="cmsbanner">
    <img src="<?php echo Yii::$app->homeUrl;?>uploads/header_image/cvUj7Aaybry_X-27DRkMg744SQWNAw2Y.jpg" class="img-responsive" alt="">
     
</div>

<div class="site-contact">
     <div class="container">
         <div class="row">
             <div class="col-sm-12" style="margin-bottom: 50px;">
   

                 <div class="contact-tag">
        If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
    </div>

    
        <div class="col-sm-6">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'name')->textInput() ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'subject') ?>

                <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

                <?=  $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

            <div class="form-group" style="text-align: right;">
                    <?= Html::submitButton('Submit', ['class' => 'btn', 'name' => 'contact-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
                 <div class="col-sm-6">
                     <div class="map-bg">
                      <img src="<?php echo Yii::$app->homeUrl;?>images/map-bg.png" class="img-responsive" alt="">
                      </div>
                      <div class="con-address">
                          <p><label>Address : </label>Washington State Office, 1102 A St #438, Tacoma, WA 98402	</p>
                          <p><label>Email : </label>info@fitmakerslive.com</p>
                          <p><label>Phone : </label>(253) 617-3805</p>
                      </div>
                 </div>
    </div>

</div>
    </div></div>
