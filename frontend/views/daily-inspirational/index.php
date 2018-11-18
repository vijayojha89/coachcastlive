<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TrainerClassSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My Classes';

?>

<!-- Start Inner Banner area -->
<div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>Daily Inspirational Wall</h2>
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
                            <h2 class="section-title-default2 title-bar-high2">Daily Inspirational Wall</h2>

                            <div class="comment-form-container">
                                <form id="frm-comment">
                                    <div class="input-row">
                                        <input type="hidden" name="comment_id" id="commentId" placeholder="Name" /> 
                                        <input type="hidden" name="user_id" id="userId" placeholder="Name" value="<?php echo \Yii::$app->user->identity->id;?>" /> 
                                        <input class="input-field" type="hidden" name="name" id="name" value="<?php echo \Yii::$app->user->identity->first_name.' '.\Yii::$app->user->identity->last_name;?>" />
                                    </div>
                                    <div class="input-row">
                                        <textarea class="input-field" type="text" name="comment"
                                            id="comment" placeholder="Add a Comment"></textarea>
                                    </div>
                                    <div>
                                        <input type="button" class="btn-submit" id="submitButton"
                                            value="Publish" /><div id="comment-message">Comments Added Successfully!</div>
                                    </div>

                                </form>
                            </div>
                            <div id="output"></div>
                           
                        </div>
                    </div>
                </div>
            </div>
</div>
<?php 

$this->registerJs('
    
function postReply(commentId) {
    $("#commentId").val(commentId);
    $("#name").focus();
}

$("#submitButton").click(function () {
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
             $("#name").val("");
             $("#comment").val("");
             $("#commentId").val("");
             listComment();
         } else
         {
             alert("Failed to add comments !");
             return false;
         }
     }
 });
});


function listComment() {
    $.post("comment-list",
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
                    var commentId = data[i]["comment_id"];
                    parent = data[i]["parent_comment_id"];

                    if (parent == "0")
                    {
                        comments = "<div class=\'comment-row\'>"+
                        "<div class=\'comment-info\'><span class=\'commet-row-label\'>from</span> <span class=\'posted-by\'>" + data[i][\'comment_sender_name\'] + " </span> <span class=\'commet-row-label\'>at</span> <span class=\'posted-at\'>" + data[i][\'date\'] + "</span></div>" + 
                        "<div class=\'comment-text\'>" + data[i][\'comment\'] + "</div>"+
                        "<div><a class=\'btn-reply\' onClick=\'postReply(10)\'>Reply</a></div></div>";

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

',  yii\web\View::POS_READY);?>

<script>

            
           
          
            

            function listReplies(commentId, data, list) {
                for (var i = 0; (i < data.length); i++)
                {
                    if (commentId == data[i].parent_comment_id)
                    {
                        var comments = "<div class='comment-row'>"+
                        " <div class='comment-info'><span class='commet-row-label'>from</span> <span class='posted-by'>" + data[i]['comment_sender_name'] + " </span> <span class='commet-row-label'>at</span> <span class='posted-at'>" + data[i]['date'] + "</span></div>" + 
                        "<div class='comment-text'>" + data[i]['comment'] + "</div>"+
                        "<div><a class='btn-reply' onClick='postReply(" + data[i]['comment_id'] + ")'>Reply</a></div>"+
                        "</div>";
                        var item = $("<li>").html(comments);
                        var reply_list = $('<ul>');
                        list.append(item);
                        item.append(reply_list);
                        listReplies(data[i].comment_id, data, reply_list);
                    }
                }
            }
        </script>