<?php 
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\file\FileInput;
$this->title = "Profile";
echo $this->render('_trainer_header.php');
$gnl = new \common\components\GeneralComponent();
$model_pwd->password_hash = ''; $model->password_hash = '';    
$model_pwd->confirm_password = ''; $model->confirm_password = ''; 
$model_pwd->old_password = ''; $model->old_password = ''; 
?>
<style>
    
    .form-control[readonly]{
        background-color: #e3e3e3!important;
    }
    #user-bio{
        height: 100px;
    }
</style>

<div id="content" class="inner_container">
  <section class="contentsection">
    <div class="container">
      <div class="row">
        <h1 class="maintitle">Profile</h1>
      </div>
    </div>
    <div class="userinformation">
      <div class="container">
        <div class="row">
          <?php if(\Yii::$app->user->identity->social_type == 0){?>
          <ul class="profiletab">
              <li class="active" style=""><a href="#PersonalInformation" class="" data-toggle="tab" data-step="1"><span class="tabname">Personal / Business Details</span> </a></li>
            <li style=""><a href="#ChangePassword" class=""  data-toggle="tab" data-step="2"><span class="tabname">Change Password</span></a></li>
          </ul>
            <?php } ?>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="mainpanel">
          <div class="tab-content profile_section"> 
            
            <!--=========================--> 
            <!-- Start Personal Information --> 
            <!--=========================-->
            
            <div id="PersonalInformation" class="tab-pane fade tabpanel in active edit_profile">
              <div class="favoritesbox userprofilebox">
                <div class="row">
                  <div class="col-xs-12 col-sm-1 pull-right"><a class="edit_profile fr" href="javascript:void(0)"><i class="sprite"></i> </a></div>
                  <div class="col-md-12">
                    <div class="info_details">
                      <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','class'=>'edit_profile tutorprofile-form'],]); ?>
                        
                      <div class="edit_profile tutorprofile-form">
                        <div class="row">
                        	<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            	<div class="info_img">
                                <div class="cui">
                                <div class="img_users"> 
                                    <a class="editimg profile_pic_block" href="javascript:void(0)"><i class="sprite"></i>
                                        <?= $form->field($model, 'profile_photo')->fileInput() ?>
                                         <i class="fa fa-pencil"></i>
                                    </a>
                                   
                                    <img id="preview" class="mCS_img_loaded" src="<?php echo $gnl->image_not_found_hb( $model->profile_photo,'profile_photo',1);?>" alt="" width="" height=""> </div>
                                </div>
                                </div>
                        	</div>
                            <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
                            	<div class="row">
                            		<div class="col-xs-12 col-sm-12">
					<?= $form->field($model, 'bio')->textarea(['row' => 10]) ?>
                                        </div>
                                        <div class="col-xs-12 col-sm-12">
                                    	<?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
                                        <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
                                        </div>
                                        <div class="col-xs-12 col-sm-12">
                                    
                                        <?= $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'autocomplete' => false, "disabled" => "disabled"]) ?>
                                        
                                        <?= $form->field($model, 'mobile_no')->textInput(['maxlength' => true]) ?>
                                        
                                        </div>
                                        
                                    <div class="col-xs-12 col-sm-12">
                                    
                                        <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
                                        
                                        <?= $form->field($model, 'state')->textInput(['maxlength' => true]) ?>
                                        
                                        </div>
                                        
                                    <div class="col-xs-12 col-sm-12">
                                    
                                        <?= $form->field($model, 'zip')->textInput(['maxlength' => true]) ?>
                                        
                                        <?= $form->field($model, 'paypal_email')->textInput(['maxlength' => true]) ?>
                                        
                                        </div>
                                        
                                    <div class="col-xs-12 col-sm-12">
                                    
                                        <?= $form->field($model, 'facebook_url')->textInput(['maxlength' => true]) ?>
                                        
                                        <?= $form->field($model, 'twitter_url')->textInput(['maxlength' => true]) ?>
                                        
                                        </div>
                                    
                                    <div class="col-xs-12 col-sm-12">
                                          <?= $form->field($model, 'google_url')->textInput(['maxlength' => true]) ?>
                                         
                                          <?php // $form->field($model, 'banner_image')->label('Banner Image <span><i class="fa fa-info-circle" aria-hidden="true" title="Recommedant size (1024x350)"></i></span>')->fileInput() ?>
                                        <!--<div class="field-user-banner-view"> 
                                            <img id="preview_video_image_0" src="<?php echo $gnl->image_not_found_hb( $model->banner_image,'banner_image'); ?>" />
                                        </div> -->
                                    </div>
                                    
                                    <!--<div class="col-xs-12 col-sm-12">
                                        <h1>Blog Subscription Fee</h1>
                                    </div>
                                    
                                    <div class="col-xs-12 col-sm-12">
                                    
                                        <?= $form->field($model, 'blog_fee_one_month')->textInput(['maxlength' => true]) ?>
                                        
                                        <?= $form->field($model, 'blog_fee_three_month')->textInput(['maxlength' => true]) ?>
                                        
                                        </div>
                                    
                                    <div class="col-xs-12 col-sm-12">
                                    
                                        <?= $form->field($model, 'blog_fee_six_month')->textInput(['maxlength' => true]) ?>
                                        
                                        <?= $form->field($model, 'blog_fee_one_year')->textInput(['maxlength' => true]) ?>
                                        
                                    </div>-->
                                    <div class="col-xs-12 col-sm-12">
                                        <h1>Schedule Call Fee</h1>
                                    </div>
                                    
                                    <div class="col-xs-12 col-sm-12">
                                    
                                        <?= $form->field($model, 'schedule_call_fee')->textInput(['maxlength' => true]) ?>
                                        
                                      
                                        
                                        </div>
			    	</div>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-10 col-sm-offset-2 pro-bun-wrap">
                  <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn', 'id' => 'updateprofilesavebutton']) ?>
                  <a href="<?php echo Url::to(['trainer/dashboard']);?>" class="btn btn-dark">Cancel</a>
              </div>
              <?php ActiveForm::end(); ?>
            </div>
            <!--==============t===========--> 
            <!-- Start Change Password --> 
            <!--=========================-->
            
            <div id="ChangePassword" class="tab-pane fade tabpanel ">
              <div class="favoritesbox userprofilebox">
                <?php $form_password = ActiveForm::begin(['id' => 'changepassword',
                              ]);?>
                <div class="row">
					<div class="col-xs-12 col-sm-4">
						<?= $form_password->field($model_pwd, 'old_password',['enableAjaxValidation' => true])->passwordInput([ 'autocomplete' => 'off'])?>
                    </div>
                    <div class="col-xs-12 col-sm-4">
						<?= $form_password->field($model_pwd, 'password_hash')->passwordInput([ 'autocomplete' => 'off']) ?>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                    	<?= $form_password->field($model_pwd, 'confirm_password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
                    </div>
		<div class="col-xs-12">
                	<p>*Atleast 8 characters with a mixture of lower, upper case or digits</p>
        		</div>  </div>        
              </div>
              <div class="col-md-12 nextbtn pro-bun-wrap">
                  <?= Html::submitButton(Yii::t('app', 'Update') , ['class' => 'btn button_btn next']) ?>
                  <a href="<?php echo Url::to(['trainer/dashboard']);?>" class="btn btn-dark">Cancel</a>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php $this->registerJs(
                
         "
$(document).on('click', '.uploadclose', function(){
     
     document.getElementById('user-cv_flag').value = 1; 
     $('#messages').html(''); 
     $('#fileselect').val('');
        
});
    ",
        \yii\web\VIEW::POS_READY); ?>
<?php $this->registerJs("
                  
            $('#fileselect').change(function (){
            
                      var fileExtension = ['doc', 'docx','pdf'];
                      if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1)
                      {
                            $('#fileselect').val('');
                            alert('Only files with these extensions are allowed: doc, docx, pdf.');
                            return false;
                      }
                      else
                      {
                           
                            var filename = document.getElementById('fileselect').files[0].name;
                            var messagedata = '<p><span>'+filename+'</span> <a class=\"uploadclose\" onclick=\"deletecv();\"></a></p>';
                                            
                            $('#messages').html(messagedata);
                      } 
                
                });
                

    ",
        \yii\web\VIEW::POS_READY); ?>
<script>
//    (function() {

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

