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
                        <h2>Availability</h2>
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
                            <h2 class="section-title-default2 title-bar-high2">Availability</h2>
                            
                            
                            
                            <div class="panel panel-default">
                                <div class="panel-heading">Month Availability</div>
                                <div class="panel-body">
                                <h4><span class="label label-default"><?php echo date('F');?></span></h4>
                                <label class="checkbox-inline checkboxbox"><input type="checkbox" value="1"> <span class="checkmark"></span>Turn On</label>
                                <label class="checkbox-inline checkboxbox"><input type="checkbox" value="0"> <span class="checkmark"></span>Turn Off</label>

                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
 </div>               
