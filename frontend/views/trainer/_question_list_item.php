
<?php
// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;
//echo '<pre>';echo '................';print_r($model);exit;   

$tnl =  new common\components\TutorComponent();  
if($_REQUEST['qstatus'] == 'completed'){$question_type = 2;}else{$question_type = 1;}
$filter = [
                'price_type'=>(isset($_REQUEST['QuestionSearch']['price_type'])&& $_REQUEST['QuestionSearch']['price_type']!='')? $_REQUEST['QuestionSearch']['price_type']:'',
                'budget_range'=>(isset($_REQUEST['QuestionSearch']['budget_range'])&& $_REQUEST['QuestionSearch']['budget_range']!='')?$_REQUEST['QuestionSearch']['budget_range']:'',
                'qualification_id'=>(isset($_REQUEST['QuestionSearch']['qualification_id'])&& $_REQUEST['QuestionSearch']['qualification_id']!='')?$_REQUEST['QuestionSearch']['qualification_id']:'',
                'subject_ids'=>(isset($_REQUEST['QuestionSearch']['subject_id'])&& $_REQUEST['QuestionSearch']['subject_id']!='')?$_REQUEST['QuestionSearch']['subject_id']:'',
                'date'=>(isset($_REQUEST['QuestionSearch']['date'])&& $_REQUEST['QuestionSearch']['date']!='')?$_REQUEST['QuestionSearch']['date']:'',
                'is_priority_set'=>(isset($_REQUEST['QuestionSearch']['is_priority_set'])&& $_REQUEST['QuestionSearch']['is_priority_set']!='')?$_REQUEST['QuestionSearch']['is_priority_set']:'',
                
           ]; 
$data_question = $tnl->tutor_questions(\Yii::$app->user->identity->id, 0, $question_type, 1, $model['question_id'], $filter, 1);

$student_data = $data_question['questions'][0]['student_details']; 
$q_data = $data_question['questions'][0];
//echo '<pre>';echo '................';print_r($data_question['questions'][0]);echo '</pre>';exit; 

$pending_hour = common\components\GeneralComponent::front_hourdifferent($model['asked_date'],$model['time_limit']);

$gnl = new \common\components\GeneralComponent();
$user_id = @Yii::$app->user->id;
$mnl = new \common\components\MasterComponent();
$question_status = $mnl->question_status($model['question_id'], $user_id);
?>
<!---------------------------accept-reject-chat-button-start--------------------------->
<?php
$accept_button = $reject_button = 0;
        if($data_question['questions'][0]['confirmed_tutor_id'] == 0 )
        {
            if( $data_question['questions'][0]['student_confirmation_status'] == 0)
            { $reject_button = 1;}
            if($data_question['questions'][0]['tutor_requst_status'] == 0 && $data_question['questions'][0]['student_confirmation_status'] == 0)
            { $accept_button = 1;}
        }
        ?>
<!---------------------------accept-reject-chat-button-end--------------------------->

<?php 
$display = '';
if($data_question['questions'][0]['confirmed_tutor_id'] != 0  &&  
   $data_question['questions'][0]['confirmed_tutor_id'] == \Yii::$app->user->identity->id)
   {$display = 'chatting'; }?>
<style>
    .chatting{
        display: block!important;
    }
</style>
<div id="Budget" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      
    </div>
  </div>
</div>
<div id="tutor-profile" class="modal fade tutor-profile" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      
    </div>
  </div>
</div>
<div class="favoritesbox myquestionbox"  id="questionlistdiv_<?php echo $data_question['questions'][0]['question_id'];?>">
                  <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                      <div class="title"><?=$model['qualification_name'];?> | <span><?=$model['subject_name'];?></span></div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                      <div class="time-info">
                        <div class="time-box">
                         <?php if($model['question_status'] == 1 && $data_question['questions'][0]['confirmed_tutor_id'] == 0){ ?>
                            <span>Time Pending</span><?php echo $pending_hour;?> Hours
                            <?php } ?>
                        <?php if($model['question_status'] == 1 && $model['is_priority_set'] == 1){ ?>
                              <em><img src="<?php echo Yii::$app->homeUrl;?>img/red-flag.png" alt=""></em>
                        <?php } ?>
                        </div>
                        <?php if($model['question_status'] == 2){?>
                          <div class="time-box"><span>Completed</span><?php echo common\components\GeneralComponent::front_date_format($model['completed_date']);?></div>
                        <?php }
                        else{
                                if($model['question_status'] != 1){
                                    ?>
                          <div class="time-box"><span style="color: red">Cancelled</span><?php echo common\components\GeneralComponent::front_date_format($model['completed_date']);?></div>
                                    <?php
                                }
                        }
                        ?>
                        <div class="posted-box"><span>Posted</span><?php echo common\components\GeneralComponent::front_date_format($model['asked_date']);?></div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="question-answer">
                        <h3><?php echo $model['title'];?></h3>
                       <p><?php echo $model['description'];?></p>
                                       
                        <?php
                        if($data_question['questions'][0]['documents'])
                        {     ?>
                        <div class="pdf-box">
                            <?php foreach ($data_question['questions'][0]['documents'] as $value) { ?>
                            <?php if($value['document_name'] != ''){?>
                            <a target="_blank" class="fileuploadanchortag" data-pjax="false" href="<?php echo $value['document_name'];?>"><img src="<?php echo common\components\GeneralComponent::get_file_type($value['original_name']);?>" alt=""><?php echo $value['original_name'];?></a>    

                            <?php } }?>
                      </div>
                       <?php } ?>
                       <?php                     
                       if($q_data['answer_id'] != 0){ 
                       ?> 
                       <div class="answer-box">
                        <div class="title">Answer </div>
                       <?php
                       if($q_data['answer']['answer_type'] == 0)
                                    { ?>
                                        <div class="chatingtext"><?= $q_data['answer']['answer_text'] ?></div>
                                    <?php } ?>
                                    
                                    <?php
                                    if($q_data['answer']['answer_type'] == 1)
                                    { 
                                     ?> 
                                    <div class="chatingtext">
                                                      <a target="_blank" class="fileuploadanchortag" data-pjax="false" href="<?=$q_data['answer']['file_name']?>">
                                                         <img src="<?=\common\components\GeneralComponent::get_file_type($q_data['answer']['file_name_original'])?>" alt="">
                                                          <?= $q_data['answer']['file_name_original']?>   
                                                       </a>    
                                                </div>
                                   <?php  } ?>
                      </div>
                        <?php
                       }
                       ?>
                      </div>
                        <?php if($model['question_status'] == 2){
                            $mnl = new common\components\MasterComponent();
                            $payment = $mnl->payment_detail($model['question_id']);  
                            if($payment['payment_amount'] != 0 || $payment['payment_amount'] != ''){
                        ?>
                      <div class="prise-btn-box">
                        <div class="time-info">
                          <div class="time-box"><span>Paid</span>£<?= $payment['payment_amount']?></div>
                          <div class="posted-box"><span>Payment</span><?= $payment['payment_type']?></div>
                        </div>
                      </div>
                        <?php } }?>
                        <div class="prise-btn-box">
                        <?php if($model['price_type'] == 1 && $model['question_status'] == 1){ ?>
                          <div class="label-btn">Fixed Price <?php echo '&nbsp;'.common\components\GeneralComponent::front_priceformat($model['price']);?></div>
                        <?php } ?>
                        <?php if($model['price_type'] == 2 && $model['question_status'] == 1){ 
                              if($data_question['questions'][0]['is_tutor_confirmed'] == 1 ){
                        ?>
                        <div class="label-btn yellow-text">Confirmed Bid &nbsp; <?=  \common\components\GeneralComponent::front_priceformat($data_question['questions'][0]['tutor_bid_amount'])?></div>
                        <?php
                              }
                              else if($data_question['questions'][0]['is_tutor_confirmed'] == 0 ){
                        ?>
                        <div class="label-btn yellow-text">Budget <?php echo '&nbsp;'.common\components\GeneralComponent::front_priceformat($model['min_budget']);?> - <?php echo '&nbsp;'.common\components\GeneralComponent::front_priceformat($model['max_budget']);?></div>
                        <?php } }?>
                      </div>
<?php

                       if(!empty($student_data))
                       { ?>                        
                        <div class="user-info <?= $display ?>" id="tutor_<?php echo $data_question['questions'][0]['question_id'];?>">
                        <div class="row">
                          <div class="col-sm-8 col-md-9">
                            <div class="accepted_user_detail"> 
                            <div class="pic-box"> <a data-toggle="modal" onClick="studentProfile(<?=$student_data['student_id']?>,<?= $reject_button?>,<?= $accept_button?>,<?=$q_data['question_id']?>,<?=$q_data['price_type']?>)"  href="javascript:void(0)"><img src="<?php echo  $student_data['profile_photo_thumb'];?>" alt=""></a></div>
                            <div class="info-box">
                              <div class="gray-text"><a data-toggle="modal" onClick="studentProfile(<?=$student_data['student_id']?>,<?= $reject_button?>,<?= $accept_button?>,<?=$q_data['question_id']?>,<?=$q_data['price_type']?>)"  href="javascript:void(0)">Student</a></div>
                              <h4><?php echo $student_data['first_name'].' '.$student_data['last_name'];?></h4>
                              <div class="sub-title"><?php echo $student_data['qualification'];?> | <span><?php echo $student_data['subjects'];?></span></div>
                              <div class="rating-ic">
                                  <?php $userobj = new \common\models\User();
                                            $ratting = $userobj->usergetrating($student_data['student_id']);
                                            $totalrating = 5;
                                            $yellowrating = $ratting['avg_rating'];
                                            $greyrating = 5-$ratting['avg_rating'];
                                            if($yellowrating)
                                            {
                                                for($i = 1;$i<=$yellowrating;$i++){ ?>
                                                <img src="<?php echo Yii::$app->homeUrl;?>img/user-star-yellow.png" alt="" width="" height="">    
                                                <?php }
                                            }  
                                            if($greyrating)
                                            {
                                                for($i = 1;$i<=$greyrating;$i++){ ?>
                                                <img src="<?php echo Yii::$app->homeUrl;?>img/user-star-gray.png" alt="" width="" height="">    
                                                <?php }
                                            }  
                                          ?>
                                          
                                          (<?php echo $ratting['no_of_user'];?>)
                              </div>
                              <?php
                                        if($data_question['questions'][0]['awaiting_student_confirmation'] == 1)
                                        { 
                                        ?>
                                        <div class="awaitingstudent">Awaiting student confirmation</div>
                                        <?php
                                        }if($data_question['questions'][0]['awaiting_student_confirmation'] == 2)
                                        { 
                                        ?>
                                        <div class="awaitingstudent">Student accepted another tutor </div>
                                        <?php
                                        }
                                        ?>
                              <?php if($question_status['student_reviewed'] == 1) {?>
                             <div class="review-box">
                                <div class="title">Reviews</div>
                                <?php echo $question_status['student_review']['comment']?>
                                <div class="date-text">
                                    <?php echo common\components\GeneralComponent::front_date_format($question_status['student_review']['created_date']);?>
                                </div>
                              </div>
                                        <?php } ?>
                            </div></div>
                          </div>
                          <div class="col-sm-4 col-md-3 text-right">
                              <!---------------------------accept-reject-chat-button-start--------------------------->
                                    <?php
                                if($data_question['questions'][0]['confirmed_tutor_id'] == 0 )
                                {
                                if( $data_question['questions'][0]['student_confirmation_status'] == 0)
                                {
                                ?>
                                <div class="close-right-box reject_tutor_question ">
                                <img style=" cursor: pointer; cursor: hand; " onclick="rejectQuestion(<?=$data_question['questions'][0]['question_id']?>, <?=\Yii::$app->user->identity->id?>)" src="<?php echo Yii::$app->homeUrl;?>img/red-close.png" alt="">

                                <?php
                                }
                                if($data_question['questions'][0]['tutor_requst_status'] == 0 && $data_question['questions'][0]['student_confirmation_status'] == 0)
                                {
                                ?>
                                <img style=" cursor: pointer; cursor: hand; " onclick="acceptQuestion(<?=$data_question['questions'][0]['question_id']?>,<?=\Yii::$app->user->identity->id?>,<?=$data_question['questions'][0]['price_type']?>)" src="<?php echo Yii::$app->homeUrl;?>img/green-right.png" alt="">
                                <?php
                                }
                                ?>
                                </div>
                                <?php
                                }
                                else if($data_question['questions'][0]['confirmed_tutor_id'] != 0  &&  $data_question['questions'][0]['confirmed_tutor_id'] == \Yii::$app->user->identity->id)
                                {
                                ?>
                                <a href="javascript:void(0)" class="chatsectionanchor" id="chatsection_<?php echo $model['question_id'].'_'.$model['created_by'];?>"><img src="<?php echo Yii::$app->homeUrl;?>img/chat.png" alt=""></a>
                            <?php if($question_status['student_reviewed'] == 1) {?>
                              <div class="review-rating-ic question_rating_block">
                                   <?php 
                                            $totalrating = 5;
                                            $yellowrating = $question_status['student_review']['rating'];
                                            $greyrating = 5-$question_status['student_review']['rating'];
                                            if($yellowrating)
                                            {
                                                for($i = 1;$i<=$yellowrating;$i++){ ?>
                                                <img src="<?php echo Yii::$app->homeUrl;?>img/user-star-yellow.png" alt="" width="" height="">    
                                                <?php }
                                            }  
                                            if($greyrating)
                                            {
                                                for($i = 1;$i<=$greyrating;$i++){ ?>
                                                <img src="<?php echo Yii::$app->homeUrl;?>img/user-star-gray.png" alt="" width="" height="">    
                                                <?php }
                                            }  
                                          ?>
                              </div>
                            <?php } ?>
                                
                                <?php
                                }
                                ?>
                                <!---------------------------accept-reject-chat-button-end--------------------------->
                                <?php
                                if($data_question['questions'][0]['is_tutor_confirmed'] == 0  &&  $data_question['questions'][0]['price_type'] == 2 &&  $data_question['questions'][0]['tutor_requst_status'] == 1)
                                {
                                ?>
                                <div style=" cursor: pointer; cursor: hand; " class="bid-btn" onclick="acceptQuestion(<?=$data_question['questions'][0]['question_id']?>,<?=\Yii::$app->user->identity->id?>,<?=$data_question['questions'][0]['price_type']?>)"><div href="#"  >Bid Submitted &nbsp;<span> <?= \common\components\GeneralComponent::front_priceformat($data_question['questions'][0]['tutor_bid_amount'])?></span></div></div>
                                <?php
                                }
                                ?>
                          </div>
                        </div>
                      </div>
<?php  
    }
    else{echo '<div class="user-info">No record found</div>';}    
?>                         
                    </div>
                  </div>
                 
                </div>

<script>

</script>    
<?php 
$this->registerJs(
                
         '
  function acceptQuestion(question_id,tutor_id,price_type) {
  var r = confirm("Are you sure?");
  if(r==true){
      if(price_type == 1){
            $.ajax({
                 method: "POST",
                 url: "accept",
                 data: {
                    question_id : question_id,
                    tutor_id : tutor_id,
                 },
                 success:function(data){
                     if(data == 1 ){
                         $("#tutor-profile").modal("hide");
                        $.pjax.reload({container:"#myquestionpjaxlist"});
                     }
                     else if(data == 2 ){
                        alert("Student has selected another tutor.");
                     }
                     else if(data == 3 ){
                        alert("This question is not available anymore.");
                     }
                  }
               })
             }
      else if(price_type == 2){
            $.ajax({
                url  : "bid-amount",
                 data: {
                    question_id : question_id,
                    tutor_id : tutor_id,
                 },
                success  : function(data) {
                $("#tutor-profile").modal("hide");
                 $("#Budget").modal("show").find(".modal-content").html(data);
                 var span = document.getElementsByClassName("close")[0];
                            span.onclick = function() {
                            $("#Budget").modal("hide");
                                                       }
                                }
                
                });
             }
    }
}
function rejectQuestion(question_id,tutor_id) {
var r = confirm("Are you sure?");
  if(r==true){
    $.ajax({
          method: "POST",
          url: "reject",
          data: {
             question_id : question_id,
             tutor_id : tutor_id,
          },
          success:function(data){
                         $("#tutor-profile").modal("hide");
            if(data == 1 ){
//                alert("Question rejected.");
                $("#questionlistdiv_"+question_id).fadeOut(500, function() { 
                        $(this).remove(); 
                });
                }
            else if(data == 3 ){
                 alert("This question is not available anymore.");
                 }
            else if(data == 2 ){
                 alert("Student has selected another tutor.");
                  }
           }
        })
    }
}
function studentProfile(student_id,reject_button,accept_button,qid,ptype) {
    hideOverflowHidden();
    $.ajax({
          method: "POST",
          url: "student-profile",
          data: {
             student_id : student_id,
             accept : accept_button,
             reject : reject_button,
             qid : qid,
             ptype : ptype,
          },
          success:function(data){
                $("#tutor-profile").modal("show").find(".modal-content").html(data);
                 var span = document.getElementsByClassName("close")[0];
                            span.onclick = function() {
                            $("#tutor-profile").modal("hide");
                            }
                            
                $(".mCustomScrollbar").mCustomScrollbar({
                    scrollButtons: {
                        enable: true,
                        scrollSpeed: 40
                    }
                });
        }
    })
}
            
            ',
        \yii\web\VIEW::POS_HEAD); ?><?php //Pjax::end(); ?>