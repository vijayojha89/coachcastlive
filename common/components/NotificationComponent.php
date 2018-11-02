<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\db\Query;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;
//use frostealth\yii2\aws\s3\interfaces\Service;
use yii\helpers\Json;
use iisns\markdown\Markdown;
use common\models\User;
use common\models\FavTutor;
use common\models\Question;
use common\models\QuestionDocument;
use yii\helpers\ArrayHelper;
use common\models\Setting;

class NotificationComponent extends Component {

	public static function send_push($devicetoken, $param, $device_type) {
        if (strtolower($device_type) == "android") {
            NotificationComponent::push_android($devicetoken, $param);
        }
        if (strtolower($device_type) == "ios") {
            NotificationComponent::push_ios($devicetoken, $param);
        }
    	}

    public static function push_android($devicetoken, $param) {
//        $apiKey = ANDROID_PUSHNOTIFICATION_API_KEY;  //demo
        $model = Setting::findOne(1);
        $apiKey = $model->push_notification_android;  //demo
        $registrationIDs[] = $devicetoken;

        // Set POST variables
        $url = ANDROID_PUSHNOTIFICATION_URL;
//print_r($param);
        $fields = array(
            'registration_ids' => $registrationIDs,
            //'notification' => $param,
                'data' => $param,
        );


        $headers = array(
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
       // echo '<pre>';print_r($result);exit;

        //print_r($result);  exit;

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        } else {
            //echo "success notification";
            //print_r($result);
            //echo curl_error($ch);
        }

        // Close connection
        curl_close($ch); 
        /*$data[]=['url'=>$url,'apikey'=>$apiKey,'devicetoken'=>$devicetoken,'param'=>$param,'result'=>$result];
        print_r($data);exit;
         * 
         */
        return $result;
    }

    
    
     public static function push_ios($devicetoken, $param) {
        
//        $Passphrase = IOS_PUSHNOTIFICATION_PASSPHRASE;
//        $PemFileName = IOS_PUSHNOTIFICATION_PEMFILENAME;
        $model = Setting::findOne(1);
        $AppleURL = IOS_PUSHNOTIFICATION_URL;
        $Passphrase = $model->push_notification_ios_password;
        $PemFileName = dirname(dirname(__DIR__)) . '/uploads/push_notification_ios/'.$model->push_notification_ios;
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $PemFileName);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $Passphrase);
        $fp = stream_socket_client($AppleURL, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
	if (!$fp){
	exit("Failed to connect: $err $errstr" . PHP_EOL);
	}

        $arr_body = array();
        $arr_body['badge'] = 0; //$this->Badge;
        $arr_body['alert'] = $param['message'];
        $arr_body['sound'] = 'default'; //$this->SoundFileName;
        $arr_body['param'] = $param;
        /*if ($param['type'] == 'chat' || $param['type'] == 'requestaccept') {
            $arr_body['data'] = $param['data'];
            $arr_body['type'] = $param['type'];
        } else {
            $arr_body['params'] = $param;
        }
         * 
         */


        $body['aps'] = $arr_body; // Create the payload body

        $payload = json_encode($body); // Encode the payload as JSON
        //print_r($payload);exit;
        $msg = chr(0) . pack('n', 32) . pack('H*', $devicetoken) . pack('n', strlen($payload)) . $payload; // Build the binary notification
        $result = fwrite($fp, $msg, strlen($msg)); // Send it to the server
	//if (!$result)
	//echo 'Message not delivered' . PHP_EOL.'<br>';
	//else
	//echo 'Message successfully delivered' . PHP_EOL.'<br>';		
	
	// Close the connection to the server
	fclose($fp);
        /*$data[]=['ios_url'=>$AppleURL,'pass'=>$Passphrase,'file'=>$PemFileName,'devicetoken'=>$devicetoken,'param'=>$param,'result'=>$result];
        print_r($data);exit;
         * 
         */
        return $result;
    }      
}
