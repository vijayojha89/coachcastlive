<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
$this->title = "Schedules";
echo $this->render('_trainer_header.php');
?>
<div class="inner_container">
    <section class="contentsection">
        <div class="container">
            <div class="row">
                <div class="col-xs-6">
                    <h1 class="maintitle">Schedules</h1>
                </div>
                <div class="col-xs-6">
                    <div class="filter-inner trainers_video_dashboard_filter">  
                        <button class="btn btn-trainers-filter"><span class="fa fa-filter"></span> Filter</button>
                        <div class="trainers-filter-box">
                            <form id="w0" action="/coachcastlive/trainer-class/index" method="get">                	
                                <div class="form-group field-trainerclasssearch-workout_type_id">
                                    <label class="control-label" for="trainerclasssearch-workout_type_id">Workout Type</label>
                                    <div class="help-block"></div>
                                </div>    
                                <div class="form-group field-trainerclasssearch-title">
                                    <label class="control-label" for="trainerclasssearch-title">Title</label>
                                    <input type="text" id="trainerclasssearch-title" class="form-control" name="TrainerClassSearch[title]">
                                    <div class="help-block"></div>
                                </div>
                                <div class="filter-btn"> 
                                    <button type="submit" class="btn">Search</button>
                                    <a href="/coachcastlive/trainer-class/index" class="btn btn-dark">Reset</a>                                
                                </div>
                            </form>                
                        </div>
                    </div>
                 </div>
             </div>
        </div>
    </section>
</div>

<?php Pjax::begin(['id' => 'myschedulepjaxlist', 'timeout' => 100000]); ?>

<div class="inner_container">
    <section class="contentsection">
        <div class="container">
            <div class="row">
                <div class="mainpanel">
                    <div class="tab-content">
                                <?php
                                echo ListView::widget([
                                    'dataProvider' => $dataProvider,
                                    'options' => [
                                        'tag' => 'div',
                                        'class' => 'list-wrapper',
                                        'id' => 'list-wrapper',
                                    ],
                                    'layout' => "{items}\n{pager}",
                                    'itemView' => function ($model, $key, $index, $widget) {
                                        return $this->render('_appointment_list_item', ['model' => $model]);
                                    },
                                ]);


                                /*
                                  ListView::widget([
                                  'dataProvider' => $dataProvider,
                                  'itemOptions' => ['class' => 'item'],
                                  'itemView' => function ($model, $key, $index, $widget) {
                                  return $this->render('_question_list_item',['model' => $model]);
                                  },
                                  'pager' => ['class' => \kop\y2sp\ScrollPager::className(),'triggerText' => 'Load More','noneLeftText'=>'']
                                  ]);
                                 * 
                                 */
                        
                            ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php Pjax::end(); ?>

<?php
$this->registerJs(
        '
             

function action_appointment(appointmentid,action)
{

     var r = confirm("Are you sure?");
     if(r!=true){
        return false;
    }
    $("#overlays").show();
    
 $.ajax({
          method: "POST",
          url: "accept-reject",
          async:"false",
          data: {
             appointment_id:appointmentid,   
             action : action,
          },
          success:function(data)
          {
            data = $.trim(data);
            
            if(data == "success")
            {
                $.pjax.reload({container:\'#myschedulepjaxlist\'});
            }
            else
            {
                //location.reload(); 
            }
            
          }
        })
        
    
    
}


', \yii\web\VIEW::POS_HEAD);
?>
<?php
 
$this->registerJs(
   '$("document").ready(function(){ 
        
$("#myschedulepjaxlist").on("pjax:start", function() {
            $("#overlays").show();
           
        });
        

$("#myschedulepjaxlist").on("pjax:end", function() {
            $("#overlays").hide();
           
        });

        
    });'
);
?>