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

$gnl = new \common\components\GeneralComponent();
$this->title = $model->title;
Yii::$app->db->createCommand("UPDATE class_online SET status=0 WHERE class_id =" . $model->trainer_class_id." AND DATE(created_date) ='".date('Y-m-d')."'")->execute();
    
    $apiKey = Yii::$app->params['openTokApiKey'];
    $apiSecret = Yii::$app->params['openTokApiSecret'];

    $opentok = new OpenTok($apiKey, $apiSecret);
    // A session that uses the OpenTok Media Router, which is required for archiving:
    $session = $opentok->createSession(array( 'mediaMode' => MediaMode::ROUTED ));
    $sessionId = $session->getSessionId();

    $token = $session->generateToken(array(
    'role'       => Role::MODERATOR,
    'expireTime' => time()+(24 * 60 * 60), // in one week
    'data'       => 'name='.Yii::$app->user->identity->first_name.'-'.Yii::$app->user->identity->last_name
    ));

    Yii::$app->db->createCommand()->insert('class_online',
    [
        'class_id' => $model->trainer_class_id,
        'session_id' => $sessionId,
        'host_token' => $token,
        'created_by' => Yii::$app->user->identity->id,
        'created_date' => date('Y-m-d H:i:s'),
    ])
    ->execute();
    $class_online_id = Yii::$app->db->getLastInsertID();
        
    $credential = [];
    $credential["apiKey"] = $apiKey;
    $credential["sessionId"] = $sessionId;
    $credential["token"] = $token;


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
<!-- Start Inner Banner area -->
<div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>Broadcast</h2>
                    </div>
                </div>
            </div>
 </div>
<!-- End Inner Banner area -->


<input type="hidden" name="class_online_id" id="class_online_id" value="<?php echo $class_online_id;?>"/>
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
                           <h2 class="section-title-default2 title-bar-high2">Broadcast</h2>
                           <button id="startStop" class="classStartStopBtn btn-broadcast hidden"><i class="fa fa-filter"></i> Start Broadcast</button>
                           <div id="credentials" style="display:none;" data='<?php echo $stringCrendital;?>'></div>
                           <div>
                             <div class="col-lg-8 main-container">
                                <div id="main" class="">
                                    <div id="videoContainer" class="video-container">
                                        <div id="hostDivider" class="hidden"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="userJoinbox">
                                <h3 style="text-align:center;"><u>Join User List</u></h3>
                                    <div id="userJoinList">
                                      
                                    </div>
                                    <div id="noUserFound"> No user found</div>
                                </div>   
                            </div>
                         </div>     

    <div class="broadcast-controls-container" >
    
        <div class="rtmp-container" style="display:none;">
            <span id="rtmpLabel">Want to stream to YouTube Live or Facebook Live? Add your RTMP Server URL and Stream Name:</span>   >
            <span class="hidden error" id="rtmpError">The entered RTMP server and/or stream name are not valid. Please check the url and try again.</span>
            <span id="rtmpActive" class="hidden active">Your RTMP stream is active!</span>
            <div id="rtmpInputContainer" class="input-container">
                <input id="rtmpServer" type="url" placeholder="rtmp://myrtmpserver/mybroadcastapp"/>
                <input id="rtmpStream" type="text" placeholder="myStreamName" />
            </div>
        </div>

                      
      
      <div id="urlContainer" class="url-container hidden" style="display:none;">
        <div id="broadcastURL" class="opacity-0 no-show"></div>
        <div id="copyURL" class="copy-link" data-clipboard-target="#broadcastURL">
          <span>Get sharable HLS link</span>
        </div>
        <div id="copyNotice" class="tooltip copy opacity-0">
            <span>Link copied to clipboard!</span>
            <span class="triangle-down">▼</span>
        </div>
      </div>
    </div>
                       
    
                            <div class="comment-form-container" id="comment-form-container" style="clear:both;">
                            <h2 class="section-title-default2 title-bar-high2" style="margin-bottom:20px;">Comments</h2>
                                <div id="output" class="notification_list"></div>
                                <div id="comment-message">Comments Added Successfully!</div>
                                    <form id="frm-comment">
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
</div>

    
<?php
 
 $this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/ramda/0.21.0/ramda.min.js', [yii\web\JqueryAsset::className()]);
 $this->registerJsFile('//cdn.jsdelivr.net/es6-promise/3.1.2/es6-promise.min.js', [yii\web\JqueryAsset::className()]);
 $this->registerJsFile('//static.opentok.com/v2/js/opentok.min.js', [yii\web\JqueryAsset::className()]);
 $this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/fetch/1.0.0/fetch.min.js', [yii\web\JqueryAsset::className()]);
 $this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.10/clipboard.min.js', [yii\web\JqueryAsset::className()]);

 $this->registerJsFile('@web/js/util/otkanalytics.js', ['depends' => [yii\web\JqueryAsset::className()]]);
 $this->registerJsFile('@web/js/util/analytics.js', ['depends' => [yii\web\JqueryAsset::className()]]);
 $this->registerJsFile('@web/js/util/http.js', ['depends' => [yii\web\JqueryAsset::className()]]);
 $this->registerJsFile('@web/js/host.js', ['depends' => [yii\web\JqueryAsset::className()]]);
 
 $this->registerCssFile('@web/css/classstyle.css');
 
 
 ?>
 
<script>

</script>

 <?php 

$this->registerJs('

$("#comment-form-container").hide();
$(document).on("click", ".replyButton", function () {
    $("#commentId").val($(this).prev().val());
    $(".replyButton").removeClass("hide"); 
    $(".message_section").addClass("hide");
    $(this).parent().prev().removeClass("hide");
    $(this).addClass("hide");
});

$(window).on("beforeunload", function(){
    var class_online_id = $("#class_online_id").val();
    $.ajax({
        url: "coachexit?id="+class_online_id,
        type: "get",
        success: function (response)
        {
          
        }
    });
});

$(document).on("click", ".postReply", function () {
    var comment = $(this).prev().val();
    if(comment == "")
    {
        alert("Comment field is required!")
        return false;
    }
    var str = $("#frm-comment").serializeArray();
        str.forEach(function (item) {
            if (item.name == "comment") {
                item.value = comment;
            }
        });

    str = $.param(str);
    $.ajax({
        url: "comment-add",
        data: str,
        type: "post",
        success: function (response)
        {
            var result = eval("(" + response + ")");
            if (response)
            {
                $("#commentId").val("");
                refreshCoachComment();
                listComment();
            } else
            {
                alert("Failed to add comments !");
                return false;
            }
        }
    });
});


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
             refreshCoachComment();
             listComment();
         } else
         {
             alert("Failed to add comments !");
             return false;
         }
     }
 });
});

$(document).on("click", ".btn-blockUser", function () {
    var classOnlineUserId = $(this).attr("id");
    var userid = $(this).attr("userid");
    $.post("blockuser?id="+classOnlineUserId,
            function (data) {
                coachUserBlock(userid);
                onlineUserList();
                listComment();
            }); 

});


$(document).on("click", ".btn-unblockUser", function () {
    var classOnlineUserId = $(this).attr("id");
    var userid = $(this).attr("userid");
    $.post("unblockuser?id="+classOnlineUserId,
            function (data) {
                coachUserUnBlock(userid);
                onlineUserList();
                listComment();
            }); 

});

window.onlineUserList=function(){
    $.post("onlineuser-list?id='.$class_online_id.'",

            function (data) {
                var data = JSON.parse(data);
                if(data.length > 0)
                {
                    $("#noUserFound").hide();
                }
                if(data.length == 0)
                {
                    $("#noUserFound").show();
                }    
                var allDivContent = "";
                for (var i = 0; (i < data.length); i++)
                {
                     
                     var imagePath = data[i][\'user_image\'];
                     var div = "<div id=\'userJoinId-"+data[i][\'class_online_user\']+"\'><ul class=\'userJoinbox-ul\'>";
                     div +=          "<li style=\'width:20%;vertical-align:top;margin-right:5px;\'><img class=\'img-circle\' src=\'"+imagePath+"\' alt=\'profile\' width=\'50\'></li>";
                     div +=           "<li style=\'width:45%;\'><b>"+ data[i][\'user_name\'] + "</b></li>";
                    
                     if(data[i][\'is_block\'] == "0")
                     {
                        div +=           "<li style=\'width:20%;text-align:right;vertical-align:top;\'><a href=\'javascript:void(0);\' id=\'"+data[i][\'class_online_user_id\']+"\' userid=\'"+data[i][\'user_id\']+"\' class=\'btn btn-danger btn-blockUser\'>Block</a></li>";
                     }
                     else
                     {
                        div +=           "<li style=\'width:20%;text-align:right;vertical-align:top;\'><a href=\'javascript:void(0);\' id=\'"+data[i][\'class_online_user_id\']+"\' userid=\'"+data[i][\'user_id\']+"\' class=\'btn btn-success btn-unblockUser\'>Unblock</a></li>";
                     }   

                     div +=           "</ul></div>";

                     allDivContent  += div;
                    
                }
                $("#userJoinList").html(\'\');
                $("#userJoinList").append(allDivContent);
            }); 
 };

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
                        comments +=             "<p class=\"message_section hide\">";
                        comments +=                 "<textarea placeholder=\'Type a message\'></textarea>";
                        comments +=                 "<input type=\'button\' class=\'btn-submit postReply\' value=\'Post Reply\'>";
                        comments +=             "</p>";
                        comments +=             "<p class=\'reply_section\'>";
                        comments +=                 "<input type=\'hidden\' class=\'hiddenCommentId\' value=\'"+commentId+"\'>";
                        comments +=                 "<input type=\'button\' class=\'pull-right btn-submit replyButton\' value=\'&nbsp;&nbsp;&nbsp;Reply&nbsp;&nbsp;&nbsp;\'>";
                        comments +=             "</p>";
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


',  yii\web\View::POS_READY);?>