<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$this->title = "My Appointment";

?>

<!-- Start Inner Banner area -->
<div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>My Appointment</h2>
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
                            <?php echo $this->render('_user_header.php'); ?>
                        </div>
                        <div class="whatclientsay">
                            <div>
                                <h2 class="section-title-default2 title-bar-high2">My Appointment</h2>
                                <!--<a class="add_vbtn" href="<?php echo Url::to(['user/addappointment']); ?>">
                                    <span><i class="fa fa-plus-circle"></i> Add New</span>
                                </a>-->
                            </div>

                            <div class="pro-rgt-middel" style="margin:0px;">
                                <div class="active-inactive" style="padding:0px;">
                                    <ul>
                                        <li><a class="<?= (!@$_REQUEST['astatus']) ? 'active' : ''; ?>" href="<?php echo Url::to(['user/myappointment']); ?>">Active</a></li>
                                        <li><a class="<?= (@$_REQUEST['astatus'] == 'completed') ? 'active' : ''; ?>" href="<?php echo Url::to(['user/myappointment', 'astatus' => 'completed']); ?>">Completed</a></li>
                                        <li><a class="<?= (@$_REQUEST['astatus'] == 'cancelled') ? 'active' : ''; ?>" href="<?php echo Url::to(['user/myappointment', 'astatus' => 'cancelled']); ?>">Cancelled</a></li>
                                    </ul>
                                </div>
                            </div>    





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
        
                                    ?>

                        </div>
                    </div>
                </div>
            </div>
</div>
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
                //$.pjax.reload({container:\'#myappointmentpjaxlist\'});
                location.reload(); 
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