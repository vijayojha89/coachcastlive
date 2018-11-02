<?php
use kartik\slider\Slider;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model frontend\models\QuestionSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<style>#steps-fivepercent-slider .ui-slider-tip {
    visibility: visible;
    opacity: 1;
    top: -30px;
}
.ui-slider-tip::before {
     content: "£"!important;

}
.select2-results__option strong{
    display:none!important;
}
</style>

<?php $settingdata = \common\models\Setting::findOne(1);



$rangevalue[0] = $settingdata['question_min_value'];
$rangevalue[1] = $settingdata['question_max_value'];

$rangevalue = implode(',', $rangevalue);

if(@$_REQUEST['range_min_max'])
{
   $rangevalue = @$_REQUEST['range_min_max'];
}    
        



?>



<?php
 
$this->registerJs(
   '$("document").ready(function(){ 
        $("#question_search_form_pjax").on("pjax:end", function() {
            
            $.pjax.reload({container:"#myquestionpjaxlist"});  //Reload GridView
            $("#overlays").hide();
            
        });
        
$("#question_search_form_pjax").on("pjax:start", function() {
            $("#overlays").show();
           
        });
        
        
        
$("#myquestionpjaxlist").on("pjax:start", function() {
            $("#overlays").show();
           
        });
        

$("#myquestionpjaxlist").on("pjax:end", function() {
            $("#overlays").hide();
           
        });

        
    });'
);
?>

<?php Pjax::begin(['id' => 'question_search_form_pjax','timeout'=> 10000]); ?>

<?php $form = ActiveForm::begin(['options' => ['data-pjax' => true ],'action' => ['myanswer'],'method'=>'get','id'=>'myquestionsearchboxfilterform']); ?>
      


<div class="boxfilter filter-answes" id="filtermainboxid">
  <div class="filterbox"> <a id="filter" role="button" data-toggle="dropdown" data-target="#" href="javascript:void(0)" ><span class="filter"><i class="filter_sprite"></i></span></a>
    <div class="dropdownfilterbox" id="filter-box" role="menu" aria-labelledby="dLabel">
        
        <input type="hidden" id="qstatus_searchform" name="qstatus" value="<?php echo @$_REQUEST['qstatus'];?>">
       
        <div class="filtermainbox" id="filtermainboxid">
        <div class="row">
              <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
             <h3>Filter & Sort</h3>
          </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            
             <?php if(@$_REQUEST['qstatus']){ ?>   
             <a href="<?php echo Url::to(['tutor/myanswer','qstatus'=>@$_REQUEST['qstatus']]);?>" class="btn btn-primary buttonreset"></a>
             <?php }else{ ?>
             <a href="<?php echo Url::to(['tutor/myanswer']);?>" class="btn btn-primary buttonreset"></a>
             <?php } ?>
        </div>
        </div>
        
        
        
        <div class="funkyradio">
         <label class="ttl_type">Price Type</label>
         <div class="funkyradio-default pricetypefancy">
            <input type="radio" name="QuestionSearch[price_type]" id="price_type_radio_fixed" value="1" <?php echo (@$_REQUEST['QuestionSearch']['price_type'] == "1")?"checked":"";?> />
            <label for="price_type_radio_fixed" class="<?php echo (@$_REQUEST['QuestionSearch']['price_type'] == "1")?"active":"";?>">Fixed</label>
        </div>
            
        <div class="funkyradio-primary pricetypefancy">
            <input type="radio" name="QuestionSearch[price_type]" id="price_type_radio_budget" value="2" <?php echo (@$_REQUEST['QuestionSearch']['price_type'] == "2")?"checked":"";?> />
            <label for="price_type_radio_budget" class="<?php echo (@$_REQUEST['QuestionSearch']['price_type'] == "2")?"active":"";?>">Budget</label>
        </div>
        </div>
        
        <div class="panel panel-default" id="budget_div_in_searchform" style="display:<?php echo (@$_REQUEST['QuestionSearch']['price_type'] == "2")?"block;":"none;";?>">
          <div class="panel-heading" role="tab" id="headingOne" style="border:none;">
            <h4 class="panel-title"> <a>Budget Range </a> </h4>
          </div>
          <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height:135px; display:block">
            <div class="panel-body">
                
                <input type="hidden" id="range_min_max" value="<?php echo $rangevalue;?>" name="range_min_max">
                <div class="slider"></div>


            </div>
          </div>
        </div>
        
        <div class="panel panel-default">
          <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo" aria-expanded="false" style="height:60px; display:block;">
            <div class="panel-body">
              <?= $form->field($model, 'qualification_id')->label(false)->dropDownList(ArrayHelper::map(common\models\Qualification::find()->where(array('status' => 1))->all(), 'qualification_id', 'name'), ['prompt' => 'Select Qualification']); ?>
            </div>
          </div>
        </div>
        <div class="panel panel-default">
          <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree" style="display:block;">
            <div class="panel-body">
                
                 <?php
                 
                    if(@$_REQUEST['QuestionSearch']['subject_ids'])
                    {
                        $model->subject_ids = $_REQUEST['QuestionSearch']['subject_ids'];
                    }

                    echo $form->field($model, 'subject_ids')
                            ->label('')
                            ->widget(Select2::classname(), [
                                
                                'data' => ['' => ArrayHelper::map(common\models\Subject::find()->where(['status' => 1])
                                            ->asArray()->all(), 'subject_id', 'name')],
                                'options' => ['multiple' => true, 'placeholder' => 'Select Subject'],
                    ]);
                    ?> 
                
                
            </div>
          </div>
        </div>
        
        
        <div class="funkyradio">
         <label class="ttl_type">Date</label>
        <div class="funkyradio-default dateorderselectionfancy">
            <input type="radio" name="QuestionSearch[dateorder]" id="dateradio1" value="1" <?php echo (@$_REQUEST['QuestionSearch']['dateorder'] == "1")?"checked":"";?> />
            <label for="dateradio1" class="<?php echo (@$_REQUEST['QuestionSearch']['dateorder'] == "1")?"active":"";?>">Older</label>
        </div>
            
        <div class="funkyradio-primary dateorderselectionfancy">
            <input type="radio" name="QuestionSearch[dateorder]" id="dateradio2" value="2" <?php echo (@$_REQUEST['QuestionSearch']['dateorder'] == "2")?"checked":"";?> />
            <label for="dateradio2" class="<?php echo (@$_REQUEST['QuestionSearch']['dateorder'] == "2")?"active":"";?>">Latest</label>
        </div>
        </div>
        
        
        
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="headingFour" style="border:none; margin-top:40px;">
            <div class="checkbox-block">
            	<span class="checkbox-title">Priority Flag</span>
            	<input type='checkbox' name='QuestionSearch[is_priority_set]' value='1' id="is_priority_set" <?php echo (@$_REQUEST['QuestionSearch']['is_priority_set'] == "1")?"checked":"";?> /><label for="is_priority_set"> </label> 
            </div>
          </div>
        </div>
        
       
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="headingFour" style="border:none; margin-top:40px;">
            <div class="checkbox_chat">
            	<span class="check-chat_ttl">Ready to Chat</span>

            	<input type='checkbox' name='QuestionSearch[confirm_select_tutor]' value='1' id="confirm_select_tutor" <?php echo (@$_REQUEST['QuestionSearch']['confirm_select_tutor'] == "1")?"checked":"";?> /><label for="confirm_select_tutor"></label> 

            </div>
          </div>
        </div>
        
        
        
        <div class="form-group">
          <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        </div>
      </div>
      
      <div class="cl"></div>
    </div>
  </div>
</div>
    
    
<?php ActiveForm::end(); ?>    
<?php
        $this->registerJs(
                '
 $(function() {
    $("body").click(function(e) {
        if (e.target.id == "filtermainboxid" || $(e.target).parents("#filtermainboxid").size() || 
        e.target.id == "s2-togall-questionsearch-subject_ids" || 
        $(e.target).parents("#s2-togall-questionsearch-subject_ids").size() ||
        e.target.id == "myquestionsearchboxfilterform" || $(e.target).parents("#myquestionsearchboxfilterform").size() ) { 
        } else { 
          $("#filter-box").hide();
        $("#filter").removeClass("active");
        $("#myquestionsearchboxfilterform").trigger("reset");
	({ backdrop: "static", keyboard: false });
        }
    });
})   
            ', \yii\web\VIEW::POS_READY);
        ?>
<?php

        
$this->registerJs('


$(".slider").slider({ 
        min: '.$settingdata['question_min_value'].', 
        max: '.$settingdata['question_max_value'].', 
        range: true, 
        values: ['.$rangevalue.'],
        slide: function( event, ui ) {
        $( "#range_min_max" ).val(ui.values[0]+","+ui.values[1]);
           
      }
    })
                        
  .slider("pips", {
        rest: false
    })
                        
    .slider("float");
    


    
    
', yii\web\View::POS_READY);
?>


<?php Pjax::end(); ?>

    


<?php

$this->registerJs('

$(document).on("click", ".filter", function (event) {
    $("#filter-box").toggle();
    $("#filter").toggleClass("active");
	
	
});

', yii\web\View::POS_READY);
?>

    

<?php $this->registerJsFile(Yii::$app->homeUrl.'js/jquery-ui.js', ['depends' => [yii\web\JqueryAsset::className()]]); ?> 
<?php $this->registerJsFile(Yii::$app->homeUrl.'js/jquery-ui-slider-pips.js', ['depends' => [yii\web\JqueryAsset::className()]]); ?> 

<?php $this->registerCssFile(Yii::$app->homeUrl."css/jquery-ui.css");?>
<?php $this->registerCssFile(Yii::$app->homeUrl."css/jquery-ui-slider-pips.css");?>