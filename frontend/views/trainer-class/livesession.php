<?php
require __DIR__."/../../../uploads/class_api/vendor/autoload.php";
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\Session;
use OpenTok\Role;
$gnl = new common\components\GeneralComponent();

$this->title = $model->title;
$classOnlineDetail = Yii::$app->db->createCommand("SELECT * FROM class_online WHERE class_id =" . $model->trainer_class_id." AND DATE(created_date) ='".date('Y-m-d')."' AND status=1")->queryOne();

if($classOnlineDetail)
{
    $apiKey = Yii::$app->params['openTokApiKey'];
    $apiSecret = Yii::$app->params['openTokApiSecret'];
    
    $opentok = new OpenTok($apiKey, $apiSecret);
    $sessionId = $classOnlineDetail['session_id'];
    $token = $opentok->generateToken($sessionId);
    
    $credential = [];
    $credential["apiKey"] = $apiKey;
    $credential["sessionId"] = $sessionId;
    $credential["token"] = $token;


    Yii::$app->db->createCommand()->insert('class_online_user',
    [
        'class_online_id' => $classOnlineDetail['class_online_id'],
        'class_id' => $classOnlineDetail['class_id'],
        'user_id' => YII::$app->user->identity->id,
        'user_name' => YII::$app->user->identity->first_name.' '.YII::$app->user->identity->last_name,   
        'user_image' => $gnl->image_not_found_hb( YII::$app->user->identity->profile_photo,'profile_photo',1),
        'created_date' => date('Y-m-d H:i:s'),
        'status' => 1,
    ])->execute();
}

$stringCrendital = json_encode($credential);
?>

<div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>Class Detail</h2>
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
                        <div>
                           <h2 class="section-title-default2 title-bar-high2">Live Session - <?php echo $model->title;?></h2>

                                <div id="credentials" style="display:none;" data='<?php echo $stringCrendital;?>'></div>
                                <div style="width:100%;">
                                    <div id="main" class="main-container" style="width:100%">
                                        <div id="banner" class="banner">
                                            <span id="bannerText" class="text">Waiting for Broadcast to Begin</span>
                                        </div>
                                        <div id="videoContainer" class="video-container">
                                            <div id="hostDivider" class="hidden"></div>
                                        </div>
                                    </div>
                                </div>
                           
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</div>

    
<?php
 
 $this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/ramda/0.21.0/ramda.min.js', [yii\web\JqueryAsset::className()]);
 $this->registerJsFile('//cdn.jsdelivr.net/es6-promise/3.1.2/es6-promise.min.js', [yii\web\JqueryAsset::className()]);
 $this->registerJsFile('//static.opentok.com/v2/js/opentok.min.js', [yii\web\JqueryAsset::className()]);
 $this->registerJsFile('@web/js/util/otkanalytics.js', ['depends' => [yii\web\JqueryAsset::className()]]);
 $this->registerJsFile('@web/js/util/analytics.js', ['depends' => [yii\web\JqueryAsset::className()]]);
 $this->registerJsFile('@web/js/viewer.js', ['depends' => [yii\web\JqueryAsset::className()]]);
 
 $this->registerCssFile('@web/css/classstyle.css');
 
 
 ?>
 
 <?php
 $this->registerJs('
 
 
 ', \yii\web\VIEW::POS_READY);?>
