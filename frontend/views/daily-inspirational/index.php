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

<style>



.comment-form-container {
	background: #F0F0F0;
	border: #e0dfdf 1px solid;
	padding: 20px;
	border-radius: 2px;
}


.comment-form-container ul {
    list-style-type: none;
    padding:20px!important;
}


.input-row {
	margin-bottom: 20px;
}

.input-field {
	width: 100%;
	border-radius: 2px;
	padding: 10px;
	border: #e0dfdf 1px solid;
}

.btn-submit {
	padding: 10px 20px;
	background: #333;
	border: #1d1d1d 1px solid;
	color: #f0f0f0;
	font-size: 0.9em;
	width: 100px;
	border-radius: 2px;
    cursor:pointer;
}



.comment-row {
	border-bottom: #e0dfdf 1px solid;
	margin-bottom: 15px;
	padding: 15px;
}

.outer-comment {

background: #F9F9F9;
padding: 20px;
border: #F9F9F9 1px solid;

}

span.commet-row-label {
	font-style: italic;
}

span.posted-by {
	color: #09F;
}

.comment-info {
	font-size: 0.8em;
}
.comment-text {
    margin: 10px 0px;
}
.btn-reply {
    font-size: 0.8em;
    text-decoration: underline;
    color: #888787;
    cursor:pointer;
}
#comment-message {
    margin-left: 20px;
    color: #189a18;
    display: none;
}
.rpl{text-align:right;}
.rpl a{ padding:0 0; margin:0 15px 0 0; color:#8cc63f; font-size:14px;}
.rpl a.btn-like{ border: 1px solid #8cc63f;
    padding: 3px 15px;
    border-radius: 3px;

}
.rpl a.btn-reply{background: #8cc63f;
    color: #fff;
    padding: 3px 15px;
    border-radius: 3px;
    text-decoration: none;

}
.comment-row .comment-info{ font-size:17px;}
span.posted-by{color:#8cc63f;}
ul.outer-comment{ width:100%; margin:0 0 15px 0; padding:0;}
ul.outer-comment li{ width:100%; margin:0 0 15px 0; padding:0 15px 0 15px;}
ul.outer-comment li ul{ width:100%; margin:0 0 15px 0; padding:0 0 0 15px;}
ul.outer-comment li ul li{ padding:0 0px 0 15px;}
textarea#comment{ padding:15px !important;}
#frm-comment input#submitButton{
    float:right; width:auto;
}
#output {

width: 100%;
margin: 0;
padding: 15px 0 0px 0;
float: left;

}


</style>

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
                            <h3>Post your thoughts to the Wall</h3>    
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

<script>

function postReply(commentId) {
    $("#commentId").val(commentId);
    $("#comment").focus();
    
}
</script>
<?php 

$this->registerJs('


    

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
                        "<div class=\'rpl\'><a class=\'btn-like\' onClick=\'postReply(" + commentId + ")\'>5 Likes</a><a class=\'btn-reply\' onClick=\'postReply(" + commentId + ")\'>Reply</a></div></div></div>";

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


function listReplies(commentId, data, list) {
    for (var i = 0; (i < data.length); i++)
    {
        if (commentId == data[i].parent_comment_id)
        {
            var comments = "<div class=\'comment-row\'>"+
            " <div class=\'comment-info\'><span class=\'commet-row-label\'>from</span> <span class=\'posted-by\'>" + data[i][\'comment_sender_name\'] + " </span> <span class=\'commet-row-label\'>at</span> <span class=\'posted-at\'>" + data[i][\'date\'] + "</span></div>" + 
            "<div class=\'comment-text\'>" + data[i][\'comment\'] + "</div>"+
            "<div class=\'rpl\'><a class=\'btn-like\' onClick=\'postReply(" + data[i][\'comment_id\'] + ")\'> 5 Likes</a><a class=\'btn-reply\' onClick=\'postReply(" + data[i][\'comment_id\'] + ")\'>Reply</a></div></div>"+
            "</div>";
            var item = $("<li>").html(comments);
            var reply_list = $(\'<ul>\');
            list.append(item);
            item.append(reply_list);
            listReplies(data[i].comment_id, data, reply_list);
        }
    }
}


',  yii\web\View::POS_READY);?>