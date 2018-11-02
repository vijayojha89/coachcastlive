<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\models\User */
/* @var $form yii\widgets\ActiveForm */
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
                <div class="row">
                    <?php
//                    echo yii\authclient\widgets\AuthChoice::widget([
//                        'baseAuthUrl' => ['/site/auth'],
//                        'popupMode' => false,
//                    ]);
                    ?>
                    <?php echo \nodge\eauth\Widget::widget(['action' => 'site/login']); ?>
                    <!--                    <a href="javascript:void(0)" class="facebook">Facebook</a>
                                        <a href="javascript:void(0)" class="google">Google</a>-->
                    <div class="cl"></div>
                    <div class="ro"><p>Or <span>Log in or Sign up with email</span></p></div>
                    <div class="col-md-12 btnlognin">
                        <div class="col-sm-6 col-md-6"><a data-toggle="modal" data-target="#myModal4" href="javascript:void(0)" onclick="hideOverflowHidden()" data-dismiss="modal" class="iwanttobe" id="btn_email_signin">Log in</a></div>
                        <div class="col-sm-6 col-md-6"><a data-toggle="modal" data-target="#myModal" href="javascript:void(0)" onclick="hideOverflowHidden()" data-dismiss="modal" class="needhelp">Sign up</a></div>
                    </div>
                    <div class = "termsofuse"><p>By signing up you agree to our <a href="<?php echo Url::to(['site/terms']);?>" target="_blank">Terms and Conditions</a> and <a href="<?php echo Url::to(['site/privacy']);?>" target="_blank">Privacy Policy</a></p></div>
                </div>
            </div>
        </div>
        <div class = "modal-footer">
            <!--<button type = "button" class = "btn btn-default" data-dismiss = "modal">Close</button> -->
        </div>
    </div>
</div>
<!--<script type="text/javascript">
$("a#btn_email_signin").click(function(){
   $( "body.home" ).addClass( "open-modal" );
}); 
$("#frm-signin .btnlognin button.btn.btn-primary").click(function(){
   $( "body.home" ).removeClass( "open-modal" );
}); 
</script>
-->