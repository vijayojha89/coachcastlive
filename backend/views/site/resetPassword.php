
<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
$settings = \common\models\Setting::findOne(['setting_id'=>1]);
$gnrl = new common\components\GeneralComponent();
?>
<div class="login-box passwordform">
    <div class="login-logo"><img src="<?php echo  $gnrl->image_not_found_hb( $settings->setting_logo_image,'setting_logo_image') ?>" alt="" /></div>
   <div class="login-box-body">


    
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'password',$fieldOptions1)->passwordInput(['autofocus' => true]) ?>
                <?= $form->field($model, 'repeat_password',$fieldOptions2)->passwordInput(['autofocus' => true]) ?>

      
    <div class="butn">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                </div>
 <div class="row">     
            <?php ActiveForm::end(); ?>
</div>
   </div>
</div>
<style>
    .passwordform form .form-group.has-error .help-block {
    font-size: 12px;
    left: 0;
   // position: absolute;
    top: 55px;
    width: 100%;
}
.butn
{
    float: right;
}
</style>