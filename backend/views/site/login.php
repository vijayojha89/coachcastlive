<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Sign In';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
$settings = \common\models\Setting::findOne(['setting_id' => 1]);
$gnrl = new common\components\GeneralComponent();
if (!empty(Yii::$app->session->getFlash('success', FALSE))) {
    ?>
    <!-- /.container -->

    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php }
?>
<div class="login-box">
    <div class="login-logo"><img src="<?php echo $gnrl->file_not_found_hb($settings->setting_logo_image, 'setting_logo_image') ?>" alt="" /></div>
    <!-- /.login-logo -->
    <div class="login-box-body">

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>
        <?=
                $form
                ->field($model, 'email', $fieldOptions1)
                ->label(false)
                ->textInput(['placeholder' => $model->getAttributeLabel('Email')])
        ?>
        <?=
                $form
                ->field($model, 'password', $fieldOptions2)
                ->label(false)
                ->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
        ?>
        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <?= Html::submitButton('Sign in', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col --> 
        </div>
        <?php ActiveForm::end(); ?>
        <?= Html::a('I forgot my password', ['site/request-password-reset']) ?>
    </div>
    <!-- /.login-box-body --> 
</div>

<!-- /.login-box --> 
