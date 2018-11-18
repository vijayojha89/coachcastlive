<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
$this->title = "My Schedules";
?>

<?php //Pjax::begin(['id' => 'myschedulepjaxlist', 'timeout' => 100000]); ?>

<!-- Start Inner Banner area -->
<div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>My Schedules</h2>
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
                            <h2 class="section-title-default2 title-bar-high2">My Schedules</h2>
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
                                ?>

                        </div>
                    </div>
                </div>
            </div>
</div>
<?php //Pjax::end(); ?>


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
            location.reload(); 
            /*
            if(data == "success")
            {
                $.pjax.reload({container:\'#myschedulepjaxlist\'});
            }
            */
            
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