<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$this->title = "My Appointment";
echo $this->render('_user_header.php');
?>

<?php Pjax::begin(['id' => 'myappointmentpjaxlist', 'timeout' => 50000]); ?>

<div class="inner_container">
    <section class="contentsection">
        <div class="container">
            <div class="row">
                <div class="col-xs-6">
                </div>  
                <div class="col-xs-6">
                     <div class="provideo-add"> 
                        <a class="add_vbtn" href="<?php echo Url::to(['user/addappointment']); ?>">
                            <span><i class="fa fa-plus-circle"></i> Add New</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="inner_container">
    <section class="contentsection">
        <div class="userinformation three-col-tab">
            <div class="container">
                <div class="row">
                    <ul class="profiletab">
                        <li class="<?= (!@$_REQUEST['astatus']) ? 'active' : ''; ?>"><a href="<?php echo Url::to(['user/myappointment']); ?>"><span class="tabname">Active</span> </a></li>
                        <li class="<?= (@$_REQUEST['astatus'] == 'completed') ? 'active' : ''; ?>"><a href="<?php echo Url::to(['user/myappointment', 'astatus' => 'completed']); ?>" class=""><span class="tabname">Completed</span></a></li>
                        <li class="<?= (@$_REQUEST['astatus'] == 'cancelled') ? 'active' : ''; ?>"><a href="<?php echo Url::to(['user/myappointment', 'astatus' => 'cancelled']); ?>"><span class="tabname">Cancelled</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
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
             

function cancelAppointment(appointmentid)
{

     var r = confirm("Are you sure?");
     if(r!=true){
        return false;
    }
    $("#overlays").show();
    
 $.ajax({
          method: "POST",
          url: "cancel-appointment",
          async:"false",
          data: {
             appointment_id:appointmentid,   
          },
          success:function(data)
          {
            data = $.trim(data);
            if(data == "success")
            {
                $.pjax.reload({container:\'#myappointmentpjaxlist\'});
            }
            else
            {
                location.reload(); 
            }
            
          }
        })
        
    $("#overlays").hide();
    
}


', \yii\web\VIEW::POS_HEAD);
?>

<?php
 
$this->registerJs(
   '$("document").ready(function(){ 
        
$("#myappointmentpjaxlist").on("pjax:start", function() {
            $("#overlays").show();
           
        });
        

$("#myappointmentpjaxlist").on("pjax:end", function() {
            $("#overlays").hide();
           
        });

        
    });'
);
?>