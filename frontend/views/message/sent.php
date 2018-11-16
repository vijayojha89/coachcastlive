<?php
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Messages';
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
                                    <li><a href="<?php echo Url::to(['message/index']); ?>">Inbox</a></li>
                                    <li class="active"><a href="javascript:void(0);">Sent</a></li>
                            </ul>
                            <?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [

            'format' => 'html',
            'label' => 'To',
            'value' => function ($data) {

                $userdata = common\models\User::findIdentity($data['to_id']);
                $gnl = new \common\components\GeneralComponent();
                $imagepath = $gnl->image_not_found_hb($userdata['profile_photo'],'profile_photo',1);

                return Html::img($imagepath, ['width' => '100', 'height' => '100']) . '<br/>' . $userdata['first_name'].' '.$userdata['last_name'];

            },
        ],

        'message_text:ntext',

        [

            'format' => 'text',
            'label' => 'Sent',
            'value' => function ($data) {

                return $data['created_date'];

            },
        ],

    ],
]);?>



                        </div>
                    </div>
                </div>
            </div>
</div>
