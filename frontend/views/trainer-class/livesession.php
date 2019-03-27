<?php
require __DIR__."/../../../uploads/class_api/vendor/autoload.php";
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\Session;
use OpenTok\Role;
$gnl = new common\components\GeneralComponent();

$this->title = $model->title;
$classOnlineDetail = Yii::$app->db->createCommand("SELECT * FROM class_online WHERE class_id =" . $model->trainer_class_id." AND DATE(created_date) ='".date('Y-m-d')."' AND status=1")->queryOne();

if($classOnlineDetail)
{
    $apiKey = Yii::$app->params['openTokApiKey'];
    $apiSecret = Yii::$app->params['openTokApiSecret'];
    
    $opentok = new OpenTok($apiKey, $apiSecret);
    $sessionId = $classOnlineDetail['session_id'];
    $token = $opentok->generateToken($sessionId);
    
    $credential = [];
    $credential["apiKey"] = $apiKey;
    $credential["sessionId"] = $sessionId;
    $credential["token"] = $token;


    Yii::$app->db->createCommand()->insert('class_online_user',
    [
        'class_online_id' => $classOnlineDetail['class_online_id'],
        'class_id' => $classOnlineDetail['class_id'],
        'user_id' => YII::$app->user->identity->id,
        'user_name' => YII::$app->user->identity->first_name.' '.YII::$app->user->identity->last_name,   
        'user_image' => $gnl->image_not_found_hb( YII::$app->user->identity->profile_photo,'profile_photo',1),
        'created_date' => date('Y-m-d H:i:s'),
        'status' => 1,
    ])->execute();
}

$stringCrendital = json_encode($credential);
?>

<style>
/* ul.outer-comment{ width:100%; margin:0 0 15px 0; padding:0;} */
/* ul.outer-comment li{ width:100%; margin:0 0 15px 0; padding:0 15px 0 15px;} */
/* ul.outer-comment li ul{ width:100%; margin:0 0 15px 0; padding:0 0 0 15px;} */
/* ul.outer-comment li ul li{ padding:0 0px 0 15px;} */
textarea#comment{ padding:15px !important;}
#frm-comment input#submitButton{
    float:right; width:auto;
}
#comment-message {
    margin-left: 20px;
    color: #189a18;
    display: none;
}

.comment-form-container .single-client-say .client-picture img {
    background: #ececec;
    border-radius: 50%;
    padding: 4px;
    height: 60px;
    width: 60px;
    transition: all 0.3s ease 0s;
}

.comment-form-container .single-client-say .client-content {
    padding-left: 10px;
}
.comment-form-container .single-client-say .client-content h3 {
    font-size:14px;
}
.comment-form-container .message_section input.btn-submit {
    background-color: #8cc63f;
    color: white;
    padding: 5px;
    margin: 0px;
    border: none;
    cursor: pointer;
    width: auto;
    /* opacity: 0.9; */
}    
.comment-form-container .reply_section input.btn-submit {
    background-color: #8cc63f;
    color: white;
    padding: 5px;
    border: none;
    cursor: pointer;
    width: auto;
    /* opacity: 0.9; */
}

.comment-form-container textarea, .comment-form-container textarea.form-control {
    /* margin: 0 !important; */
    /* border: none !important; */
    border: 1px solid #eee !important;
    padding: 5px!important;
    /* font-size: 14px; */
    width: 100%;
}
.notification_list ul li ul {
    margin-left:10%;
}
</style>
<div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>Class Detail</h2>
                    </div>
                </div>
            </div>
 </div>
<!-- End Inner Banner area -->


<input type="hidden" name="hidden_user_id" id="hidden_user_id" value="<?php echo \Yii::$app->user->identity->id;?>" /> 
<div class="online-store-grid padding-space">
            <div class="container">
                <div class="row">
                    <?php echo $this->render('//user/_left_sidebar.php'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-9">
                        <div class="pro-rgt-top">
                            <?php echo $this->render('//user/_user_header.php'); ?>
                        </div>
                        <div class="whatclientsay">
                        <div>
                           <h2 class="section-title-default2 title-bar-high2">Live Session - <?php echo $model->title;?></h2>

                                <div id="credentials" style="display:none;" data='<?php echo $stringCrendital;?>'></div>
                                <div style="width:100%;">
                                    <div id="main" class="main-container" style="width:100%">
                                        <div id="banner" class="banner">
                                            <span id="bannerText" class="text">Waiting for Broadcast to Begin</span>
                                        </div>
                                        <div id="videoContainer" class="video-container">
                                            <div id="hostDivider" class="hidden"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="comment-form-container" style="">
                                <div id="output" class="notification_list"></div>
                                <div id="comment-message">Comments Added Successfully!</div>
                                <?php
                                $styleform="";
                                if($userClassDetail['is_block'] == 1)
                                {
                                    $styleform = "display:none;";
                                }
                                ?>
                                    <form id="frm-comment" style="<?php echo $styleform;?>">
                                        <div class="input-row">
                                            <input type="hidden" name="comment_id" id="commentId" placeholder="Name" /> 
                                            <input type="hidden" name="user_id" id="userId" placeholder="Name" value="<?php echo \Yii::$app->user->identity->id;?>" /> 
                                            <input type="hidden" name="user_image" id="userImage" value="<?php echo $gnl->image_not_found_hb(\Yii::$app->user->identity->profile_photo, 'profile_photo', 1); ?>" /> 
                                            <input type="hidden" name="class_session_id" id="class_session_id" value="<?php echo $credential['sessionId'];?>" /> 
                                            <input class="input-field" type="hidden" name="name" id="name" value="<?php echo \Yii::$app->user->identity->first_name.' '.\Yii::$app->user->identity->last_name;?>" />
                                        </div>
                                        <div class="input-row">
                                            <textarea class="input-field" type="text" name="comment"
                                                id="comment" placeholder="Add a Comment"></textarea>
                                        </div>
                                        <div>
                                            <input type="button" class="btn-submit" id="submitButton"
                                                value="Publish" />
                                        </div>

                                    </form>
                            </div>
                           
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</div>

    
<?php
 
 $this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/ramda/0.21.0/ramda.min.js', [yii\web\JqueryAsset::className()]);
 $this->registerJsFile('//cdn.jsdelivr.net/es6-promise/3.1.2/es6-promise.min.js', [yii\web\JqueryAsset::className()]);
 $this->registerJsFile('//static.opentok.com/v2/js/opentok.min.js', [yii\web\JqueryAsset::className()]);
 $this->registerJsFile('@web/js/util/otkanalytics.js', ['depends' => [yii\web\JqueryAsset::className()]]);
 $this->registerJsFile('@web/js/util/analytics.js', ['depends' => [yii\web\JqueryAsset::className()]]);
 $this->registerJsFile('@web/js/viewer.js', ['depends' => [yii\web\JqueryAsset::className()]]);
 
 $this->registerCssFile('@web/css/classstyle.css');
 
 
 ?>
 
 <?php
 $this->registerJs('
 
 
$("#submitButton").click(function () {
    if($.trim($("#comment").val())  == "")
    {
        alert("Comment field is required!")
        return false;
    }

    $("#comment-message").css("display", "none");
 var str = $("#frm-comment").serialize();

 $.ajax({
     url: "comment-add",
     data: str,
     type: "post",
     success: function (response)
     {
         var result = eval("(" + response + ")");
         if (response)
         {
             $("#comment-message").css("display", "inline-block");
             $("#comment-message").fadeOut(5000);
             $("#comment").val("");
             $("#commentId").val("");
             refreshCommentUser();   
             listComment();
             
         } else
         {
             alert("Failed to add comments !");
             return false;
         }
     }
 });
});
 
window.listComment=function(){
    $.post("comment-list?id='.$credential['sessionId'].'",
             function (data) {
                    var data = JSON.parse(data);
                 
                 var comments = "";
                 var replies = "";
                 var item = "";
                 var parent = -1;
                 var results = new Array();
 
                 var list = $(\'<ul class="outer-comment">\');
                 var item = $("<li>").html(comments);
 
                 for (var i = 0; (i < data.length); i++)
                 {
                     var commentId = data[i]["class_comment_id"];
                     parent = data[i]["parent_comment_id"];
 
                     if (parent == "0")
                     {
                         comments = "<div class=\'notification_item\'>";
                         comments +=    "<div class=\'single-client-say\'>";
                         comments +=         "<div class=\'pull-left client-picture\'>";
                         comments +=                "<img src=\'"+data[i][\'user_image\']+"\'>";
                         comments +=          "</div>";
                         comments +=          "<div class=\'media-body client-content\'>";
                         comments +=             "<h3>"+ data[i][\'user_name\'] +"</h3><p>"+data[i][\'comment\'] +"</p>";
                         comments +=          "</div>";
                         comments +=     " </div>";
                         comments += "</div>";
 
                         var item = $("<li>").html(comments);
                         list.append(item);
                         var reply_list = $("<ul>");
                         item.append(reply_list);
                         listReplies(commentId, data, reply_list);
                     }
                 }
                 $("#output").html(list);
             }); 
 }
 
 listComment();
 
 window.listReplies=function(commentId, data, list){
   
     for (var i = 0; (i < data.length); i++)
     {
         if (commentId == data[i].parent_comment_id)
         {
             comments = "<div class=\'notification_item\'>";
                         comments +=    "<div class=\'single-client-say\'>";
                         comments +=         "<div class=\'pull-left client-picture\'>";
                         comments +=               "<img src=\'"+data[i][\'user_image\']+"\'>";
                         comments +=          "</div>";
                         comments +=          "<div class=\'media-body client-content\'>";
                         comments +=             "<h3>"+ data[i][\'user_name\'] +"</h3><p>"+data[i][\'comment\'] +"</p>";
                         comments +=          "</div>";
                         comments +=     " </div>";
                         comments += "</div>";
 
             var item = $("<li>").html(comments);
             var reply_list = $(\'<ul>\');
             list.append(item);
             item.append(reply_list);
             listReplies(data[i].comment_id, data, reply_list);
         }
     }
 }
 
 ', \yii\web\VIEW::POS_READY);?>
