<?php 


use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
$this->title = "Notifications";

if(\Yii::$app->user->identity->role == 'user')
{
echo $this->render('//user/_user_header.php');
}
else if(\Yii::$app->user->identity->role == 'trainer')
{
echo $this->render('//trainer/_trainer_header.php');
}
?>
 
  <!-- ============ Content Section ========= -->
  <div id="content" class="inner_container notification_listing">
    <section class="contentsection">
    
    <div class="container">
    	<div class="row">
     <h1 class="maintitle">Notifications</h1>
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
                    'itemOptions' => [
        'class' => 'notification_item',
    ],
                    'layout' => "{items}\n{pager}",
                    'itemView' => function ($model, $key, $index, $widget) {
                        return $this->render('_notification_list_item',['model' => $model]);
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
function removeNotification(id) {
    $.ajax({
            url: "remove-notification",
            type: "GET",
            data : {
                id : id,
            },
            async: false,
            success: function (data) {
                  $("#notifilistdiv_"+id ).fadeOut(500, function(){ $(this).remove();});
            },
        });
}
  
$(".list-wrapper").children().not(".pagination").wrapAll("<div class=notification_list />");

            
            ', \yii\web\VIEW::POS_END);
?>