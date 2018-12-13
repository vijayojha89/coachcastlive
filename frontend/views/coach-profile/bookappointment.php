<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\components\GeneralComponent;
use yii\bootstrap\Tabs;
use kartik\datetime\DateTimePicker;
use yii\widgets\Pjax;
$this->title = "Add Appointment";
?>


<!-- Start Inner Banner area -->
<div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>Coach Profile</h2>
                    </div>
                </div>
            </div>
 </div>
<!-- End Inner Banner area -->


<div class="online-store-grid padding-space">
            <div class="container">
                <div class="row">
                    <?php echo $this->render('//coach-profile/_left_sidebar.php',['model'=>$usermodel]); ?>
                    <div class="col-lg-9 col-md-9 col-sm-9">
                        <div class="pro-rgt-top">
                            <?php //echo $this->render('_user_header.php'); ?>
                        </div>
                        <div class="whatclientsay">
                            <h2 class="section-title-default2 title-bar-high2">Book Appointment</h2>
                            
                            <div class="tab-content">
                            <?php $form = ActiveForm::begin(['id' => 'form-addappointment']); ?>
                            <?= $form->field($model, 'title', ['enableAjaxValidation' => true])->textInput(['autocomplete' => 'off']) ?>
                            <?= $form->field($model, 'reason', ['enableAjaxValidation' => true])->textarea(['row'=>'20']) ?>
                            <?php echo $form->field($model, 'appointment_date')->widget(DateTimePicker::classname(), [
                                        'options' => ['placeholder' => 'Enter Date & time ...','autocomplete'=>"off"],
                                        'pluginOptions' => [
                                            'autoclose' => true
                                        ]
                                    ]); ?>
                                    
                                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-outline btn-success', 'id' => 'saveAppointment']) ?>

                        <?php ActiveForm::end(); ?>
                    </div>
                            

                        </div>
                    </div>
                </div>
            </div>
</div>



<?php $this->registerJs('   $(document).on("click", "#saveAppointment", function(){
                                        
                                    $("#form-addappointment").yiiActiveForm("validateAttribute", "appointment-title");
                                    $("#form-addappointment").yiiActiveForm("validateAttribute", "appointment-reason");
                                    $("#form-addappointment").yiiActiveForm("validateAttribute", "appointment-appointment_date");
                                    
                                    
                            });

                            
', yii\web\View::POS_READY); ?>

