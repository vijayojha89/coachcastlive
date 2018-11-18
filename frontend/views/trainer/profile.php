<?php
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
$this->title = "Profile";
$gnl = new \common\components\GeneralComponent();
$model_pwd->password_hash = '';
$model->password_hash = '';
$model_pwd->confirm_password = '';
$model->confirm_password = '';
$model_pwd->old_password = '';
$model->old_password = '';
?>
<style>

    .form-control[readonly]{
        background-color: #e3e3e3!important;
    }
    #user-bio{
        height: 100px;
    }
</style>


<!-- Start Inner Banner area -->
<div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>Edit Profile</h2>
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
                            <?php echo $this->render('//user/_user_header.php'); ?>
                        </div>
                        <div class="whatclientsay">
                            <h2 class="section-title-default2 title-bar-high2">Edit Profile</h2>
                            <div class="row">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#PersonalInformation">Personal / Business Details</a></li>
                                    <li><a data-toggle="tab" href="#ChangePassword">Change Password</a></li>
                                </ul>


                                 <div class="tab-content Profile-contnet">
                                    <div id="PersonalInformation" class="tab-pane fade in active">
                                    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'edit_profile tutorprofile-form']]);?>
                                        <div class="img_users">
                                            <!-- <//?=$form->field($model, 'profile_photo')->fileInput()?> -->
                                             <?php echo $form->field($model, 'profile_photo', [
                                                'template' => '{label}<span class="fileupload">Upload profile pic</span> {input}{error}{hint}'
                                                ])->fileInput(['autofocus' => true,'value'=>'','class'=>'form-control']) ;
                                                ?>
                                            <img id="preview" class="mCS_img_loaded" src="<?php echo $gnl->image_not_found_hb($model->profile_photo, 'profile_photo', 1); ?>" alt="" width="200" height="200"> 
                                        </div>
                                        <?=$form->field($model, 'bio')->textarea(['row' => 10])?>
                                        <div class="height20"></div>
                                        
                                        <?=$form->field($model, 'first_name')->textInput(['maxlength' => true])?>
                                       
                                        <?=$form->field($model, 'last_name')->textInput(['maxlength' => true])?>
                                        <?=$form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'autocomplete' => false, "disabled" => "disabled"])?>
                                        <?=$form->field($model, 'mobile_no')->textInput(['maxlength' => true])?>
                                        <?=$form->field($model, 'city')->textInput(['maxlength' => true])?>
                                        <?=$form->field($model, 'state')->textInput(['maxlength' => true])?>
                                        <?=$form->field($model, 'zip')->textInput(['maxlength' => true])?>
                                        <?=$form->field($model, 'paypal_email')->textInput(['maxlength' => true])?>
                                        <?=$form->field($model, 'facebook_url')->textInput(['maxlength' => true])?>
                                        <?=$form->field($model, 'twitter_url')->textInput(['maxlength' => true])?>
                                         <?=$form->field($model, 'google_url')->textInput(['maxlength' => true])?>
                                        <?=$form->field($model, 'schedule_call_fee')->textInput(['maxlength' => true])?>
                                        <div class="row">
                                            <?=Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn', 'id' => 'updateprofilesavebutton'])?>
                                            <a href="<?php echo Url::to(['trainer/dashboard']); ?>" class="btn btn-dark">Cancel</a>
                                        </div>
                                        <?php ActiveForm::end();?>
                                    </div>
                                    <div id="ChangePassword" class="tab-pane fade">
                                    <?php $form_password = ActiveForm::begin(['id' => 'changepassword']);?>
                                        <?=$form_password->field($model_pwd, 'old_password', ['enableAjaxValidation' => true])->passwordInput(['autocomplete' => 'off'])?>
                                        <?=$form_password->field($model_pwd, 'password_hash')->passwordInput(['autocomplete' => 'off'])?>
                                        <?=$form_password->field($model_pwd, 'confirm_password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off'])?>
                                        <p>*Atleast 8 characters with a mixture of lower, upper case or digits</p>
                                        <div class="col-md-12 nextbtn pro-bun-wrap">
                                            <?=Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn button_btn next'])?>
                                            <a href="<?php echo Url::to(['trainer/dashboard']); ?>" class="btn btn-dark">Cancel</a>
                                        </div>
                                  <?php ActiveForm::end();?>      
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 </div>               

 
<script>
    var URL = window.URL || window.webkitURL;
    var input = document.querySelector('#user-profile_photo');
    var preview = document.querySelector('#preview');

    // When the file input changes, create a object URL around the file.
    input.addEventListener('change', function () {

        var FileUploadPath = this.files[0].name;
        var Extension = FileUploadPath.substring(FileUploadPath.lastIndexOf('.') + 1).toLowerCase();
        if (Extension == "gif" || Extension == "png" || Extension == "bmp" || Extension == "jpeg" || Extension == "jpg")
        {
                preview.src = URL.createObjectURL(this.files[0]);
        }
        else
        {
           alert("Profile image only allows file types of GIF, PNG, JPG, JPEG and BMP. ");
           document.getElementById('user-profile_photo').value='';
           document.getElementById('preview').src = BASE_URL+'/uploads/no_image/no_image.jpg';
           return false;
        }


    });

    // When the image loads, release object URL
    preview.addEventListener('load', function () {
        URL.revokeObjectURL(this.src);
       // alert('jQuery code here. W: ' + this.width + ', H: ' + this.height);
    });
</script>