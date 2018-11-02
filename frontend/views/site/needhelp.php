<?php

use yii\helpers\Url;
use common\components\GeneralComponent;

$settings = new common\models\Setting();
$settings = $settings->findOne(["status" => 1]);
?>

<!-- Modal -->
<div id="myModal" class="modal fade popupwidth" role="dialog">
    <!--<div class="modal-dialog">
        <div class="modal-content needquestion">
            <div class="modal-header">
                <button type="button" onclick="myFunctionClose()" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title loing-logo"><img src="<?= Url::base(true) ?>/img/logo.png" alt="FitMakersLive" width="" height=""></h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12 needquestion">
                    <div class="row">
                        <a data-toggle="modal" onclick="hideOverflowHidden()" data-target="#myModal2" href="javascript:void(0)" data-dismiss="modal" class="iwanttobe" id="student-signup">I need help with a question </a>
                        <a data-toggle="modal" onclick="hideOverflowHidden()" data-target="#myModal3" href="javascript:void(0)" data-dismiss="modal" class="iwanttobe" id="tutor-signup">I want to be a tutor </a>
                        <div class="cl"></div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>-->
    
    
    <div class="modal-dialog">

    <!-- Modal content-->
     <div class="modal-content">   
      <div class="modal-body-signup"> 
      			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
            <div class="modal-signup">
            	
                <h3 style="text-align: center;">Create a personal account to take advantage.</h3>
               <br/>
                  <div class="signup-stap">
                  		<a href="<?php echo Url::to(['user/signup-trainer']); ?>" class="signup-box">
                        	<img src="<?= Url::base(true) ?>/images/stap-icon.png">
                  			<span>Join As Trainer</span>
                  		</a>
                        <a href="<?php echo Url::to(['user/signup-user']); ?>" class="signup-box">
                        	<img src="<?= Url::base(true) ?>/images/stap-icon2.png">
                  			<span>Join As User</span>
                  		</a>
                  </div>
            </div>
      </div>
       
    </div>

  </div>
</div>

<div id="myModal1" class="modal fade popupwidth " role="dialog"></div>
<div id="myModal2" class="modal fade popupwidth " role="dialog"></div>
<div id="myModal3" class="modal fade popupwidth " role="dialog"></div>
<div id="myModal4" class="modal fade popupwidth " role="dialog"></div>
<div id="pwdModal" class="modal fade popupwidth" role="dialog"></div>

<!-- Forgot pass -->
<div id="myModal5" class="modal fade popupwidth " role="dialog"></div>

<?php

?>

           
 <?php
        $this->registerJs(
                "
                    

$('.modal').on('show.bs.modal', function() {
    $('#overlays').show();
})

$('.modal').on('shown.bs.modal', function() {
    $('#overlays').hide();
})

            ", \yii\web\VIEW::POS_READY);
        ?>
                
              
