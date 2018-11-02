<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\components\GeneralComponent;
use yii\helpers\Url;
use kartik\file\FileInput;
$this->title = "Sign Up";
/* @var $this yii\web\View */
/* @var $model frontend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>



<section class="signup-section">
    <div class="container">
        <div class="row">

            <div class="loginpage-block">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">   
                        <div class="modal-body-login">

                            <div class="modal-signup">
                                <h2>Sign Up - User</h2>

                                <?php
                                $form = ActiveForm::begin([
                                            'id' => 'frm-signup-tutor',
                                            'enableAjaxValidation' => true,
                                            'enableClientValidation' => true,
                                            'options' => ['enctype' => 'multipart/form-data', 'class' => 'registerform']
                                ]);
                                ?>


                                <?= $form->field($model, 'social_type')->hiddenInput()->label(false) ?>
                                <?= $form->field($model, 'social_id')->hiddenInput()->label(false) ?>
                                <?= $form->field($model, 'user_type')->hiddenInput(['maxlength' => true])->label(false) ?>


                                <?php
                                echo $form->field($model, 'first_name', [
                                    'template' => '{label}<i class="fa fa-user" aria-hidden="true"></i> {input}{error}{hint}'
                                ]);


                                echo $form->field($model, 'last_name', [
                                    'template' => '{label}<i class="fa fa-user" aria-hidden="true"></i> {input}{error}{hint}'
                                ]);

                                echo $form->field($model, 'mobile_no', [
                                    'template' => '{label}<i class="fa fa-phone" aria-hidden="true"></i> {input}{error}{hint}'
                                ]);

                                echo $form->field($model, 'email', [
                                    'template' => '{label}<i class="fa fa-envelope" aria-hidden="true"></i> {input}{error}{hint}'
                                ]);
                                ?>


<?php if (!isset($model->social_type) || $model->social_type == 0) { ?>

                                    <?php
                                    /*
                                      echo $form->field($model, 'password_hash', [
                                      'template' => '{label}<i class="fa fa-key" aria-hidden="true"></i> {input}{error}{hint}'
                                      ]);
                                      echo $form->field($model, 'confirm_password', [
                                      'template' => '{label}<i class="fa fa-key" aria-hidden="true"></i> {input}{error}{hint}'
                                      ]);
                                     */
                                    ?>

                                    <?=
                                    $form->field($model, 'password_hash', [
                                        'template' => '{label}<i class="fa fa-key" aria-hidden="true"></i> {input}{error}{hint}'
                                    ])->passwordInput()
                                    ?>

                                    <?=
                                    $form->field($model, 'confirm_password', [
                                        'template' => '{label}<i class="fa fa-key" aria-hidden="true"></i> {input}{error}{hint}'
                                    ])->passwordInput()
                                    ?>

<?php } ?>

                                <div class="signup-bottom"> 
<?php echo Html::submitButton('Sign Up', ['class' => 'btn signup-btn']) ?>
                                </div>



                                <p class="footer_privacy_policy">
                                    By signing up you agree to our <a href="<?php echo Url::to(['/site/terms']); ?>" target="_blank">Terms and Conditions</a> and <a href="<?php echo Url::to(['/site/privacy']); ?>" target="_blank">Privacy Policy</a>
                                </p>

                                <div class="modal-footer">
                                    <p class="footer_signin">
                                        Already have an account ?<br />
                                        <a data-toggle="modal" data-target="#myModal4" href="javascript:void(0)" data-dismiss="modal" id="btn_email_signin">Log in to continue</a>
                                    </p>

                                </div>

<?php ActiveForm::end(); ?>


                            </div>
                        </div>

                    </div>

                </div>
            </div>


        </div>
    </div>
</section>