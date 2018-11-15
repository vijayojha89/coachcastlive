<?php

use yii\widgets\ListView;
use yii\widgets\Pjax;
$this->title = "Notifications";
?>
<!-- Start Inner Banner area -->
<div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>Notifications</h2>
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
                            <h2 class="section-title-default2 title-bar-high2">Notifications</h2>
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
                                                return $this->render('_notification_list_item', ['model' => $model]);
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