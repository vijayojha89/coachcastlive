<?php
require __DIR__."/../../../uploads/class_api/vendor/autoload.php";
use yii\helpers\Html;
use yii\helpers\Url;

use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\Session;
use OpenTok\Role;

$apiKey = Yii::$app->params['openTokApiKey'];
$apiSecret = Yii::$app->params['openTokApiSecret'];

$opentok = new OpenTok($apiKey, $apiSecret);


$sessionNeed = 1;
if($appointmentDetail)
{
    if($appointmentDetail['sessionId'])
    {
        $sessionNeed = 0;
        $sessionId = $appointmentDetail['sessionId'];
        $token = $appointmentDetail['token'];
    }
}


if($sessionNeed == 1)
{   
    $session = $opentok->createSession();
    $sessionId = $session->getSessionId();
    $token = $opentok->generateToken($sessionId);
    Yii::$app->db->createCommand("UPDATE appointment_confirm SET sessionId='".$sessionId."',token='".$token."' WHERE appointment_confirm_id ='".$appointmentDetail['appointment_confirm_id']."'")->execute();
}


$credential = [];
$credential["apiKey"] = $apiKey;  
$credential["sessionId"] = $sessionId;
$credential["token"] = $token;
$stringCrendital = json_encode($credential);
  

$this->title = "Video Call";
?>

<div id="credentials" style="display:none;" data='<?php echo $stringCrendital;?>'></div>
<input type="hidden" name="userName" id="userName" value="<?php echo Yii::$app->user->identity->first_name.'    '.Yii::$app->user->identity->last_name;?>"/>
<!-- Start Inner Banner area -->
<div class="inner-banner-area">
	<div class="container">
		<div class="row">
			<div class="innter-title">
				<h2>Videos</h2>
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
                    <h2 class="section-title-default2 title-bar-high2">Video Call</h2>
                    <button id="exitClass" class="exitbtn btn-danger"><i class="fa fa-sign-out"></i>Exit</button>
                    </div>
                    <div style="clear:both;"></div>
					<div id="timerSection" class="hidden timerSection">
						<span class="style colorDefinition size_lg">Remaining Time - <span id="m_timer"></span></span>
                    </div>
                    

                    <div class="App">
                            <div class="App-main">
                                <div id="controls" class='App-control-container hidden'>
                                    <div class="ots-video-control circle audio" id="toggleLocalAudio"></div>
                                    <div class="ots-video-control circle video" id="toggleLocalVideo"></div>
                                </div>
                                <div class="App-video-container" id="appVideoContainer">
                                    <div class="App-mask" id="connecting-mask">
                                        <progress-spinner dark style="font-size:50px"></progress-spinner>
                                        <div class="message with-spinner">Connecting</div>
                                    </div>
                                    <div class="App-mask hidden" id="start-mask">
                                        <div class="message button clickable" id="start">Click to Start Call</div>
                                    </div>
                                    <div id="cameraPublisherContainer" class="video-container hidden"></div>
                                    <div id="screenPublisherContainer" class="video-container hidden"></div>
                                    <div id="cameraSubscriberContainer" class="video-container-hidden"></div>
                                    <div id="screenSubscriberContainer" class="video-container-hidden"></div>
                                </div>
                                <div id="chat" class="App-chat-container hidden"></div>
                            </div>
                        </div>    



					

				</div>
			</div>
		</div>
	</div>
</div>




<?php
 
$this->registerJsFile('//static.opentok.com/v2/js/opentok.min.js', [yii\web\JqueryAsset::className()]);
$this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js', [yii\web\JqueryAsset::className()]);
$this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js', [yii\web\JqueryAsset::className()]);
//$this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/livestamp/1.1.2/livestamp.min.js', [yii\web\JqueryAsset::className()]);


$this->registerJsFile('@web/js/components/opentok-solutions-logging.js', ['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/components/opentok-text-chat.js', ['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/components/opentok-screen-sharing.js', ['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/components/opentok-annotation.js', ['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/components/opentok-archiving.js', ['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/components/opentok-acc-core.js', ['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/videoCall.js', ['depends' => [yii\web\JqueryAsset::className()]]);

$this->registerCssFile('@web/css/video_style.css');


?>

<?php
$this->registerJs('


', \yii\web\VIEW::POS_READY);?>