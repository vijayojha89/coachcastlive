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


$this->title = $model->title;
Yii::$app->db->createCommand("UPDATE class_online SET status=0 WHERE class_id =" . $model->trainer_class_id." AND DATE(created_date) ='".date('Y-m-d')."'")->execute();
    
    $apiKey = "46240312";
    $apiSecret = "f370782db6a592c836700ac30ed325f1ced5ec42";

    $opentok = new OpenTok($apiKey, $apiSecret);
    // A session that uses the OpenTok Media Router, which is required for archiving:
    $session = $opentok->createSession(array( 'mediaMode' => MediaMode::ROUTED ));
    $sessionId = $session->getSessionId();

    $token = $session->generateToken(array(
    'role'       => Role::MODERATOR,
    'expireTime' => time()+(24 * 60 * 60), // in one week
    'data'       => 'name='.Yii::$app->user->identity->first_name.'-'.Yii::$app->user->identity->last_name
    ));

    Yii::$app->db->createCommand()->insert('class_online',
    [
        'class_id' => $model->trainer_class_id,
        'session_id' => $sessionId,
        'host_token' => $token,
        'created_by' => Yii::$app->user->identity->id,
        'created_date' => date('Y-m-d H:i:s'),
    ])
    ->execute();
    
        
    $credential = [];
    $credential["apiKey"] = $apiKey;
    $credential["sessionId"] = $sessionId;
    $credential["token"] = $token;


$stringCrendital = json_encode($credential);
?>

<!-- Start Inner Banner area -->
<div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>Broadcast</h2>
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
                           <h2 class="section-title-default2 title-bar-high2">Broadcast</h2>
                           <div id="credentials" style="display:none;" data='<?php echo $stringCrendital;?>'></div>
                         
                           <div>
                             <div class="col-lg-8 main-container">
                                <div id="main" class="">
                                    <div id="videoContainer" class="video-container">
                                        <div id="hostDivider" class="hidden"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="userJoinbox">
                                <h3 style="text-align:center;"><u>Join User List</u></h3>
                                    <div id="userJoinList">
                                        <div id="noUserFound"> No user found</div>
                                    </div>
                                </div>   
                            </div>
                         </div>     

    <div class="broadcast-controls-container" >
        <div class="rtmp-container" style="display:none;">
            <span id="rtmpLabel">Want to stream to YouTube Live or Facebook Live? Add your RTMP Server URL and Stream Name:</span>   >
            <span class="hidden error" id="rtmpError">The entered RTMP server and/or stream name are not valid. Please check the url and try again.</span>
            <span id="rtmpActive" class="hidden active">Your RTMP stream is active!</span>
            <div id="rtmpInputContainer" class="input-container">
                <input id="rtmpServer" type="url" placeholder="rtmp://myrtmpserver/mybroadcastapp"/>
                <input id="rtmpStream" type="text" placeholder="myStreamName" />
            </div>
        </div>
      <button id="startStop" class="btn-broadcast hidden">
        Start Broadcast
      </button>
      <div id="urlContainer" class="url-container hidden" style="display:none;">
        <div id="broadcastURL" class="opacity-0 no-show"></div>
        <div id="copyURL" class="copy-link" data-clipboard-target="#broadcastURL">
          <span>Get sharable HLS link</span>
        </div>
        <div id="copyNotice" class="tooltip copy opacity-0">
            <span>Link copied to clipboard!</span>
            <span class="triangle-down">▼</span>
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
 $this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/fetch/1.0.0/fetch.min.js', [yii\web\JqueryAsset::className()]);
 $this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.10/clipboard.min.js', [yii\web\JqueryAsset::className()]);

 $this->registerJsFile('@web/js/util/otkanalytics.js', ['depends' => [yii\web\JqueryAsset::className()]]);
 $this->registerJsFile('@web/js/util/analytics.js', ['depends' => [yii\web\JqueryAsset::className()]]);
 $this->registerJsFile('@web/js/util/http.js', ['depends' => [yii\web\JqueryAsset::className()]]);
 $this->registerJsFile('@web/js/host.js', ['depends' => [yii\web\JqueryAsset::className()]]);
 
 $this->registerCssFile('@web/css/classstyle.css');
 
 
 ?>
 
 <?php
 $this->registerJs('
 
 
 ', \yii\web\VIEW::POS_READY);?>