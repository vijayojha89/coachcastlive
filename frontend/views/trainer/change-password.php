<?php
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
// echo '<pre>'; print_r($model_pwd);exit;
?>
<div id="ChangePassword" class="tab-pane fade tabpanel ">
                <div class="favoritesbox userprofilebox">
                    <?php $form_password = ActiveForm::begin(['id' => 'changepassword',
                              ]);?>
                    <?= $form_password->field($model_pwd, 'old_password',['enableAjaxValidation' => true])->passwordInput([ 'autocomplete' => 'off'])?>
                    <?= $form_password->field($model_pwd, 'password_hash')->passwordInput([ 'autocomplete' => 'off']) ?>
                    <?= $form_password->field($model_pwd, 'confirm_password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
                    <p>*Atleast 8 characters with a mixture of lower, upper case, digits and special characters</p>
                    <div class="col-md-12 nextbtn">
                  <?= Html::submitButton(Yii::t('app', 'Save') , ['class' => 'btn btn-default button_btn next']) ?>
                </div>
                  <?php ActiveForm::end(); ?>
                </div>
              </div>