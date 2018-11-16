<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Messages';

YII::$app->db->createCommand()->update('message', ['is_read'=>1], 'to_id = '.YII::$app->user->identity->id)->execute();

?>

<!-- Start Inner Banner area -->
<div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>Messages</h2>
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
                            <h2 class="section-title-default2 title-bar-high2">Messages</h2>
                            <ul class="nav nav-tabs">
                                    <li class="active"><a href="javascript:void(0);">Inbox</a></li>
                                    <li><a href="<?php echo Url::to(['message/sent']); ?>">Sent</a></li>
                            </ul>

                            <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            [

            'format' => 'html',
            'label' => 'From',
            'value' => function ($data) {

                    $userdata = common\models\User::findIdentity($data['from_id']);
                    $gnl = new \common\components\GeneralComponent();
                    $imagepath = $gnl->image_not_found_hb($userdata['profile_photo'],'profile_photo',1);
                    return Html::img($imagepath, ['width' => '100', 'height' => '100']).'<br/>'.$userdata['first_name'].' '.$userdata['last_name'];
                
            },
        ],
           
             'message_text:ntext',
                    
             [

            'format' => 'text',
            'label' => 'Received',
            'value' => function ($data) {

                    return $data['created_date'];
                
            },
        ],       
           
                    [
                        'label' => '',                        
                        'value' => function ($data) {
                            return Html::a('Reply', ['message/sendmessage','id'=>$data['from_id']], [
                                            'class' => 'btn', 'data-toggle'=>'modal','data-target'=>'#message-modal'
                                ]);
                            
                            
                            
                                    
                           
                        },
                        'format' => 'raw',
                    ],
                                
        ],
    ]); ?>

                        </div>
                    </div>
                </div>
            </div>
</div>

<div class="modal fade" id="message-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="margin-top: 100px;">
    <div class="modal-content">
      
      

    </div>
  </div>
</div>   