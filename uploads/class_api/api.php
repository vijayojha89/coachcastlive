<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Content-Type:application/json");

require "vendor/autoload.php";
use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\Session;
use OpenTok\Role;
use OpenTok\Layout;

$apiKey = "46240312";
$apiSecret = "f370782db6a592c836700ac30ed325f1ced5ec42";
$opentok = new OpenTok($apiKey, $apiSecret);
$request_body = file_get_contents('php://input');
$requestData = json_decode($request_body);

if($requestData->action=="start")
{
  $sessionId = $requestData->sessionId;
    $options = array(
      'layout' => Layout::getBestFit(),
      'maxDuration' => 5400,
      'resolution' => '1280x720'
  );
  $broadcast = $opentok->startBroadcast($sessionId, $options);
  echo $broadcastResponse = json_encode(['broadcastId'=>$broadcast->id]);
  die;
}  

if($requestData->action=="end")
{
  $session = $requestData->session;
  // $broadcastId = $_SESSION['broadcastId'];
  // $opentok->stopBroadcast($broadcastId);
  $opentok->forceDisconnect($session->session_id, $session->connectionId);
  echo "success";
  die;
}  




?>
