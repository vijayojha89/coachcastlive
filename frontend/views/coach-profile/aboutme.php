<?php
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
$this->title = "About Me";
?>
<style>

    .form-control[readonly]{
        background-color: #e3e3e3!important;
    }
    #user-bio{
        height: 100px;
    }
    .field-user-bio{
        max-width:100%!important;
    }
</style>


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
                    <?php echo $this->render('//coach-profile/_left_sidebar.php',['model'=>$model]); ?>
                    <div class="col-lg-9 col-md-9 col-sm-9">
                        <div class="pro-rgt-top">
                            <?php //echo $this->render('//user/_user_header.php'); ?>
                        </div>
                        <div class="whatclientsay ">
                            <h2 class="section-title-default2 title-bar-high2">About Me</h2>
                            <div class="editpro_page">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#PersonalInformation">Personal Details</a></li>
                                </ul>


                                 <div class="tab-content Profile-contnet">
                                    <div id="PersonalInformation" class="tab-pane fade in active">
                                    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'edit_profile tutorprofile-form']]);?>
                                        <?=$form->field($model, 'bio')->textarea(['row' => 20,'disabled'=>'disabled'])?>
                                        <?=$form->field($model, 'first_name')->textInput(['maxlength' => true,'disabled'=>'disabled'])?>
                                       
                                        <?=$form->field($model, 'last_name')->textInput(['maxlength' => true,'disabled'=>'disabled'])?>
                                        <?=$form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'autocomplete' => false, "disabled" => "disabled"])?>
                                        <?=$form->field($model, 'mobile_no')->textInput(['maxlength' => true,'disabled'=>'disabled'])?>
                                        <?=$form->field($model, 'city')->textInput(['maxlength' => true,'disabled'=>'disabled'])?>
                                        <?=$form->field($model, 'state')->textInput(['maxlength' => true,'disabled'=>'disabled'])?>
                                        <?=$form->field($model, 'zip')->textInput(['maxlength' => true,'disabled'=>'disabled'])?>
                                        <?=$form->field($model, 'schedule_call_fee')->textInput(['maxlength' => true,'disabled'=>'disabled'])?>

<div class="" style="margin-left:15px;">
Follow Me On Social Network
<div class="social-icons">
                                    <ul class="social-link">
                                        <li class="first">
                                            <a class="facebook" href="<?php echo $model->facebook_url;?>" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                        </li>
                                        <li class="second">
                                            <a class="twitter" href="<?php echo $model->twitter_url;?>" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                        </li>
                                        <li class="fourth">
                                            <a class="google" href="<?php echo $model->google_url;?>" target="_blank"><i class="fa fa-google" aria-hidden="true"></i></a>
                                        </li>

                                    </ul>
                                </div></div>
                                       

                                        <div class="clearfix"></div>
                                        <?php ActiveForm::end();?>
                                    </div>
                               </div>
                            
                                <div class="clearfix"></div>
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