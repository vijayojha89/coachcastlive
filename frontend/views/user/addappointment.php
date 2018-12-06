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
                        <h2>Add New Appointment</h2>
                    </div>
                </div>
            </div>
 </div>
<!-- End Inner Banner area -->


<div class="online-store-grid padding-space">
            <div class="container">
                <div class="row">
                    <?php echo $this->render('//user/_left_sidebar.php'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-9">
                        <div class="pro-rgt-top">
                            <?php echo $this->render('_user_header.php'); ?>
                        </div>
                        <div class="whatclientsay">
                            <h2 class="section-title-default2 title-bar-high2">My Appointment</h2>
                            
                            <div class="tab-content">
                            <?php $form = ActiveForm::begin(['id' => 'form-addappointment']); ?>
                            <?= $form->field($model, 'title', ['enableAjaxValidation' => true])->textInput(['autocomplete' => 'off']) ?>
                            <?= $form->field($model, 'reason', ['enableAjaxValidation' => true])->textarea() ?>
                            <?php echo $form->field($model, 'appointment_date')->widget(DateTimePicker::classname(), [
                                        'options' => ['placeholder' => 'Enter Date & time ...','autocomplete'=>"off"],
                                        'pluginOptions' => [
                                            'autoclose' => true
                                        ]
                                    ]); ?>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fa fa-user fa-fw"></i> Select Trainer
                                </div>
                                <div class="panel-body">
                                    <div class="list-group">
                                                <?php
                                                $usercomponentmodel = new common\components\UserComponent();
                                                $recommeded_trainer_data = $usercomponentmodel->recommended_trainer(Yii::$app->user->id, $subject, 0);
                                                $gnl = new \common\components\GeneralComponent();
                                                if (@$recommeded_trainer_data['recommended_trainer']) {
                                                    foreach($recommeded_trainer_data['recommended_trainer'] as $value){ ?>
                                                    
                                                     <div class="panel panel-default addappointmenttrainerlist">
                                                         <div class="panel-heading"><?php echo $value['first_name'].' '.$value['last_name'];?>
                                                         <label class="container">
                                                                <input type="radio" value="<?php echo $value['id'];?>" name="Appointment[trainer_id]">
                                                                 <span class="checkmark"></span>
                                                            </label>
                                                         </div> 
                                                          <div class="panel-body">
                                                            <div class="pic-box"> 
                                                                <a> <img class="img-circle" src="<?php echo $gnl->image_not_found_hb($value['profile_photo'], 'profile_photo', 1); ?>"></a>
                                                            </div> 
                                                            <div class="info-box">
                                                                    <p><?php echo $value['bio'];?></p>
                                                                    <p><h4>Fee Charge - <span>$<?php echo $value['schedule_call_fee'];?></span></h4></p>
                                                            </div>
                                                          </div>    
                                                     </div>
                                                    <?php } } else { ?>
                                                    <div>
                                                       
                                                          No record found.
                                                        
                                                    </div>
                                                <?php } ?>

                                                
                                            </div>
                                        </div>
                                    </div>

                             
                                    <?= Html::a(Yii::t('app', 'Cancel'), ['user/myappointment'], ['class' => 'btn btn-outline btn-danger']) ?>
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
                                    
                                    var atLeastOneIsChecked = false;
                                    $("input:radio[name=\'Appointment[trainer_id]\']").each(function () {
                                      if ($(this).is(":checked")) {
                                        atLeastOneIsChecked = true;
                                        return false;
                                      }
                                    });
  
                                    if(atLeastOneIsChecked)
                                    {
                                      return true;
                                    }
                                    else
                                    {
                                      alert("Please select trainer");
                                      return false;
                                    }
                            });

                            
', yii\web\View::POS_READY); ?>

