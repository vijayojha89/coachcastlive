<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\components\GeneralComponent;
use yii\bootstrap\Tabs;
use kartik\datetime\DateTimePicker;
use yii\widgets\Pjax;
Pjax::begin(['id' => 'addappointmentPage', 'timeout' => 50000]);
$this->title = "Add Appointment";
echo $this->render('_user_header.php');
?>
<style>
    .tab-pane {
        display: none;
    }
</style>
<div class="inner_container">
    <section class="contentsection">
        <div class="container">
            <div class="row">
                <h1 class="maintitle">Add Appointment</h1>
                <div class="mainpanel">
                    <div class="tab-content">
                        <?php $form = ActiveForm::begin(['id' => 'form-addappointment']); ?>
                        <!--=========================--> 
                        <!-- Start Details section --> 
                        <!--=========================-->
                        <div id="details" class="">
                            <div class="control-form">
                                <div class="col-md-12">
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
                                                    <div class="favoritesbox_botttom">
                                                        <ul class="favorites_select">
                                                            <li> No record found.</li>
                                                        </ul>
                                                    </div>
                                                <?php } ?>

                                                
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div align="center">
                                    <?= Html::a(Yii::t('app', 'Cancel'), ['user/myappointment'], ['class' => 'btn btn-outline btn-danger']) ?>
                                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-outline btn-success', 'id' => 'saveAppointment']) ?>
                                </div>
                            </div>
                        </div>

                        <!--=========================--> 
                        <!-- Start Payment --> 
                        <!--=========================-->

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
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

<?php Pjax::end(); ?>