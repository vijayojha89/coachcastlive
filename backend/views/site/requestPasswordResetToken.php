<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
$settings = \common\models\Setting::findOne(['setting_id'=>1]);
?>

<div class="login-box">
		<div class="login-logo"><img src="<?php echo Yii::$app->params['httpurl'] . 'setting_logo_image/'.$settings->setting_logo_image ?>" alt="" /></div>
		<div class="login-box-body">
				<h1 class="reset-password">
						<?= Html::encode($this->title) ?>
				</h1>
				<p class="reset-password">Please fill out your email. A link to reset password will be sent there.</p>
				<?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
				<?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
				
				<div class="row">
						<div class="col-xs-8"> </div>
						<!-- /.col -->
						<div class="col-xs-4">
								<?= Html::submitButton('Send', ['class' => 'btn btn-primary btn-block btn-flat']) ?>
						</div>
						<!-- /.col --> 
				</div>
				<?php ActiveForm::end(); ?>
					<p><a href="<?= \yii\helpers\Url::to(['/site'])?>"><i class="fa fa-arrow-left"></i> Log in</a></p>
		</div>
</div>
