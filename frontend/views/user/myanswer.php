<?php 
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$this->title = "My Answer";
echo $this->render('_tutor_header.php');

?>
<div class="inner_container">
    <section class="contentsection">
      <div class="container">
        <div class="row">
          <div class="col-md-8 myquestion">
            <h1 class="maintitle fl">My Answers</h1>
          </div>           
          <div class="col-md-4 boxfilter filter-answes">
             <?php echo $this->render('_questionsearch', ['model' => $searchModel]); ?>          
            </div>
          </div>
        </div>
    </section>
</div>

<?php Pjax::begin(['id'=>'myquestionpjaxlist','timeout'=> 10000]); ?>

<div class="inner_container">
    <section class="contentsection">
 <?php // if(!isset($_REQUEST['qid'])){ ?>       
<div class="userinformation">
        <div class="container qu">
          <div class="row">
            <ul class="profiletab">
              <li class="<?=(!@$_REQUEST['qstatus'])?'active':'';?>"><a href="<?php echo Url::to(['tutor/myanswer']);?>"><span class="tabname">Active</span> </a></li>
              <li class="<?=(@$_REQUEST['qstatus'] == 'completed')?'active':'';?>"><a href="<?php echo Url::to(['tutor/myanswer','qstatus'=>'completed']);?>" class=""><span class="tabname">Completed</span></a></li>
            </ul>
          </div>
        </div>
      </div>
         <?php // } ?>
      <div class="container">
        <div class="row">
          <div class="mainpanel">
            <div class="tab-content">
				<?=
                ListView::widget([
                    'dataProvider' => $dataProvider,
                    'options' => [
                        'tag' => 'div',
                        'class' => 'list-wrapper',
                        'id' => 'list-wrapper',
                    ],
                    'layout' => "{items}\n{pager}",
                    'itemView' => function ($model, $key, $index, $widget) {
                        return $this->render('_question_list_item',['model' => $model]);
                    },
                ]);
                ?>
               
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<?php
        $this->registerJs(
                '
             
     $(".profiletab li a").click(function(){
        
        $("#filter-box").hide();
        $("#filter").removeClass("active");
        $("#myquestionsearchboxfilterform").trigger("reset");
		({ backdrop: "static", keyboard: false });
     });
     


     
$( ".updown" ).click(function() {

$(this).parent().find(".user-info").toggle("slow");
$(".favoritesbox.myquestionbox" ).toggleClass("arrowclass");
});			

            ', \yii\web\VIEW::POS_READY);
        ?>
<?php Pjax::end(); ?>


<div id="chat-section">
</div>

<?php
        $this->registerJs(
                '
             
$(document).on("click", ".profiletab li a", function(){

        
        

        var qstatus = $(this).find("span").html().toLowerCase();
        if(qstatus  == "active")
        {
        qstatus = "";
        }
        
        $("#filter-box").hide();
        $("#filter").removeClass("active");
        $("#qstatus_searchform").val(qstatus);
        $("#questionsearch-qualification_id").val("");
        $("#questionsearch-subject_id").val("");
        $("#range_min_max").val("0,100");
        $("#is_priority_set").removeAttr("checked");
        $("#confirm_select_tutor").removeAttr("checked");
        
        
        $(".funkyradio input").removeAttr("checked");
        $(".funkyradio label").removeClass("active");
        $(".slider").slider("option", "values", [0, 1000]);
        $("#budget_div_in_searchform").hide();
        $("#questionsearch-subject_ids").val("");
        
        if(qstatus)
        {
            var resethref = BASE_URL+"/tutor/myanswer?qstatus="+qstatus;
        }
        else
        {
            var resethref = BASE_URL+"/tutor/myanswer";
        }
        
        $(".buttonreset").attr("href",resethref);
});
        


    $(document).on("click", ".pricetypefancy label", function(){
            $(".pricetypefancy label").removeClass("active");
            $(this).addClass("active");
    });
    
$(document).on("click", ".dateorderselectionfancy label", function(){
            $(".dateorderselectionfancy label").removeClass("active");
            $(this).addClass("active");
    });
    



$(document).on("click", "#price_type_radio_budget", function(){
    $("#budget_div_in_searchform").show();
});


$(document).on("click", "#price_type_radio_fixed", function(){
     $("#budget_div_in_searchform").hide();
});




$(document).on("click", ".chatsectionanchor", function(){

        $("body").addClass("hidescrollbar");
        var anchor_id = $(this).attr("id");
        var question_receiver_array = anchor_id.split("_");
        var chat_question_id = question_receiver_array[1];
        var chat_receiver_id =  question_receiver_array[2];
        
        $.ajax({
            url: BASE_URL + "/chat/start",
            type: "GET",
            data:{
                  chat_question_id:chat_question_id,  
                  chat_receiver_id:chat_receiver_id,  
            },
            async: false,
            success: function (data) {
                if (data != "")
                {
                    $("#chat-section").html("");
                    $("#chat-section").html(data);
                    $("#chat-section").addClass("chatshow");
                }
                
                    
    $(".mCustomScrollbar").mCustomScrollbar({
                    scrollButtons: {
                        enable: true,
                        scrollSpeed: 40
                    }
                });
            },
        });
        
 });
 

$(document).on("click", "#chatclose", function(){

        $("#chat-section").html("");
	$("#chat-section").removeClass("chatshow");
        $("body").removeClass("hidescrollbar");
});


            
            ', \yii\web\VIEW::POS_READY);
        ?>