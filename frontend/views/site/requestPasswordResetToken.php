<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use common\components\GeneralComponent;
?>
<!-- Modal -->
<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content needquestion">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title loing-logo"><img src="<?= Url::base(true) ?>/img/logo.png" alt="FitMakersLive" width="" height=""></h4>
        </div>
        <div class="modal-body">
            <div class="col-md-12 needquestion">
                <p>Please enter your registered email to reset password:</p>
                <div class="row">
                    <?php
                    $form = ActiveForm::begin([
                                'id' => 'frm-pwd-reset',
                                'enableAjaxValidation' => true,
                                'enableClientValidation'=>false,
                                'options' => ['enctype' => 'multipart/form-data', 'class' => 'registerform']
                    ]);
                    ?>
                    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                    <div class="col-md-12 btnlognin">
                        <div class="col-md-12">
                            <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <div class = "modal-footer">
            <!--<button type = "button" class = "btn btn-default" data-dismiss = "modal">Close</button> -->
        </div>
    </div>
</div>