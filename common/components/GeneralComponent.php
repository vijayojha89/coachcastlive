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
use common\models\NotificationGeneralization;

class GeneralComponent extends Component {

    public static function wordcutoff($string,$character)
    {
        $string = strip_tags($string);

        if (strlen($string) > $character) {

              $stringCut = substr($string, 0, $character);
              $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
        }
        return $string;
    }   
    
    public static function verify_token($token)
    {
        
        if($token)
        {    
        
            
            $user = \common\models\User::find()->where(['service_token'=>trim($token)])->one();

            if($user != NULL){
                return $user;
            }else{
                return NULL;
            }
        }
        else
        {
            return NULL;
        }    
    }
    public static function getUnreadMsgCount($user_id) {
        $unread_notifications_count = Yii::$app->db->createCommand("
                                        SELECT COUNT(1) FROM notification_generalization
                                        WHERE notification_to ='" . $user_id . "' and status=1 AND is_read = 0")
                                    ->queryScalar();
        
        return $unread_notifications_count;
    }
    public static function saveNotificationLog($coachcastlive_id = NULL, $notification_from, $notification_to, $notification_type, $notification_text, $creator) {
        $model = new NotificationGeneralization;
        $model->coachcastlive_id = $coachcastlive_id;
        $model->notification_from = $notification_from;
        $model->notification_to = $notification_to;
        $model->notification_type = $notification_type;
        $model->notification_text = $notification_text;
        $model->is_read = 0; //mark as unread
        $model->created_date = date("Y-m-d H:i:s"); 
        $model->created_by = $creator;
        $model->status = 0;	
        $model->save(false);
    }
    public function is_active($table_name, $status, $column_name, $id) {

        $command = Yii::$app->db->createCommand()
                ->update($table_name, ['status' => $status], "$column_name = $id")
                ->execute();
    }

    public function fileupload($path, $directory, $model, $field_name) {

        if (!file_exists($path . $directory)) {
            mkdir($path . $directory, 0777, true);
        }
        Yii::$app->params['uploadPath'] = $path . $directory . '/';
        $image = UploadedFile::getInstance($model, $field_name);


        if (!empty($image->name)) {
            $model->$field_name = $image->name;
            $ext = (explode(".", $image->name));
            $ext = end($ext);

            // generate a unique file name
            $model->$field_name = Yii::$app->security->generateRandomString() . ".{$ext}";
            // the path to save file, you can set an uploadPath
            // in Yii::$app->params (as used in example below)
            $path = Yii::$app->params['uploadPath'] . $model->$field_name;
            $image->saveAs($path);
            if ($directory != 'setting_favicon_image') {
                $this->resize_image(Yii::$app->params['uploadPath'], $model->$field_name);
            }
        }
    }

    public function fileupload_File($path, $directory, $model, $field_name) {

        if (!file_exists($path . $directory)) {
            mkdir($path . $directory, 0777, true);
        }
        Yii::$app->params['uploadPath'] = $path . $directory . '/';
        $file = UploadedFile::getInstance($model, $field_name);


        if (!empty($file->name)) {
            $model->$field_name = $file->name;
            $ext = (explode(".", $file->name));
            $ext = end($ext);
            $img_name = array_shift((explode(".", $file->name)));
            $img_updated_name = str_replace(' ','_',strtolower($img_name)).time();
            $model->$field_name = $img_updated_name . ".{$ext}";
            // generate a unique file name
//            $model->$field_name = Yii::$app->security->generateRandomString() . ".{$ext}";
            // the path to save file, you can set an uploadPath
            // in Yii::$app->params (as used in example below)
            $path = Yii::$app->params['uploadPath'] . $model->$field_name;
            $file->saveAs($path);
        }
    }

    public function fileuploadwebservice($path, $directory, $model, $field_name) {

        if (!file_exists($path . $directory)) {
            mkdir($path . $directory, 0777, true);
        }
        Yii::$app->params['uploadPath'] = $path . $directory . '/';
        $image = UploadedFile::getInstanceByName($field_name);

        if (!empty($image->name)) {
            $model->$field_name = $image->name;
            $ext = end((explode(".", $image->name)));

            // generate a unique file name
            $model->$field_name = Yii::$app->security->generateRandomString() . ".{$ext}";
            // the path to save file, you can set an uploadPath
            // in Yii::$app->params (as used in example below)
            $path = Yii::$app->params['uploadPath'] . $model->$field_name;
            $image->saveAs($path);
            if($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'gif' ){
            GeneralComponent::resize_image(Yii::$app->params['uploadPath'], $model->$field_name);
            }
        }
    }
    
    public function fileuploadwebservicemultiple($path, $directory, $model, $field_name,$map_key,$map_val) {
        if (!file_exists($path . $directory)) {
            mkdir($path . $directory, 0777, true);
        }
        Yii::$app->params['uploadPath'] = $path . $directory . '/';
        $file_array = UploadedFile::getInstancesByName($field_name);
        foreach ($file_array as $file) {
                if (!empty($file->name)) {
                    $model = new $model();
                    $model->$field_name = $file->name;
                    $ext = end((explode(".", $file->name)));
                    $model->$field_name = Yii::$app->security->generateRandomString() . ".{$ext}";
                    $model->$map_key = $map_val;
                    $model->original_name = $file->name; // for question_document
                    $path = Yii::$app->params['uploadPath'] . $model->$field_name;
                    $file->saveAs($path);
                    if($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'gif' ){
                    GeneralComponent::resize_image(Yii::$app->params['uploadPath'], $model->$field_name);
                   // $model->document_type = 1;
                    }
                    $model->save(FALSE);
                }
        }
    }
    public function filedocuploadwebservice($path, $directory, $model, $field_name,$image = '') {

        if (!file_exists($path . $directory)) {
            mkdir($path . $directory, 0777, true);
        }
        Yii::$app->params['uploadPath'] = $path . $directory . '/';
        if($image == '')
        {
            $image = UploadedFile::getInstanceByName($field_name);
        }
        if (!empty($image->name)) {
            $model->$field_name = $image->name;
            $ext = end((explode(".", $image->name)));
            $img_name = array_shift((explode(".", $image->name)));
            $img_updated_name = str_replace(' ','_',strtolower($img_name)).time();
            // generate a unique file name
            $model->$field_name = $img_updated_name . ".{$ext}";
            // the path to save file, you can set an uploadPath
            // in Yii::$app->params (as used in example below)
            $path = Yii::$app->params['uploadPath'] . $model->$field_name;
            $image->saveAs($path);
            if($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'gif' ){
            GeneralComponent::resize_image(Yii::$app->params['uploadPath'], $model->$field_name);
            }
        }
    }

    public function resize_image($path, $filename) {
        if (!file_exists($path . 'thumb')) {
            mkdir($path . 'thumb', 0777, true);
        }
        Image::frame($path . $filename, 0, 'ffffff', 100)
                ->thumbnail(new Box(200, 200))
                ->save($path . 'thumb/' . $filename, ['quality' => 50]);
    }

    public function image_not_found($image, $directory) {

        if (empty($image)) {

            $path = $path = Yii::$app->params['httpurl'] . 'no_image/no_image.jpg';
        } else {
            if (file_exists(\realpath('../../') . '/uploads/' . $directory . '/thumb/' . $image)) {

                $path = Yii::$app->params['httpurl'] . $directory . '/thumb/' . $image;
            } else {
                $path = Yii::$app->params['httpurl'] . 'no_image/no_image.jpg';
            }
        }
        return $path;
    }

    

    public function image_not_found_profile_hb($image, $directory) {

        if (substr($image, 0, 7) === "http://" || substr($image, 0, 8) === "https://") {

            if (empty($image)) {

                $path = $path = Yii::$app->params['httpurl'] . 'no_image/profile.jpg';
            } else {
                $path = $image;
            }
            return $path;
        } else {
            if (empty($image)) {

                $path = $path = Yii::$app->params['httpurl'] . 'no_image/profile.jpg';
            } else {
                if (file_exists(\realpath('../../') . '/uploads/' . $directory . '/thumb/' . $image)) {

                    $path = Yii::$app->params['httpurl'] . $directory . '/thumb/' . $image;
                } else {
                    $path = Yii::$app->params['httpurl'] . 'no_image/profile.jpg';
                }
            }
            return $path;
        }
    }
    
    public function image_not_found_profile_hb_main($image, $directory) {

        if (substr($image, 0, 7) === "http://" || substr($image, 0, 8) === "https://") {

            if (empty($image)) {

                $path = $path = Yii::$app->params['httpurl'] . 'no_image/profile.jpg';
            } else {
                $path = $image;
            }
            return $path;
        } else {
            if (empty($image)) {

                $path = $path = Yii::$app->params['httpurl'] . 'no_image/profile.jpg';
            } else {
                if (file_exists(\realpath('../../') . '/uploads/' . $directory . '/' . $image)) {

                    $path = Yii::$app->params['httpurl'] . $directory . '/' . $image;
                } else {
                    $path = Yii::$app->params['httpurl'] . 'no_image/profile.jpg';
                }
            }
            return $path;
        }
    }

    public function image_not_found_hb($image, $directory,$type = '') {

        if (substr($image, 0, 7) === "http://" || substr($image, 0, 8) === "https://") {

            if (empty($image)) {

                $path = $path = Yii::$app->params['httpurl'] . 'no_image/no_image.jpg';
            } else {
                $path = $image;
            }
            return $path;
        } else {
            if (empty($image)) {
                $path = $path = Yii::$app->params['httpurl'] . 'no_image/no_image.jpg';
            } else {
                if($type == 1){
                    if (file_exists(\realpath('../../') . '/uploads/' . $directory . '/' . $image)) {

                    $path = Yii::$app->params['httpurl'] . $directory . '/' . $image;
                } else {
                    $path = Yii::$app->params['httpurl'] . 'no_image/no_image.jpg';
                }
                }
                else {
                    if (file_exists(\realpath('../../') . '/uploads/' . $directory . '/thumb/' . $image)) {

                    $path = Yii::$app->params['httpurl'] . $directory . '/thumb/' . $image;
                } else {
                    $path = Yii::$app->params['httpurl'] . 'no_image/no_image.jpg';
                }
                }
                
            }
            return $path;
        }
    }
    
 public function image_not_found_hb_o($image, $directory) {

        if (substr($image, 0, 7) === "http://" || substr($image, 0, 8) === "https://") {

            if (empty($image)) {

                $path = $path = Yii::$app->params['httpurl'] . 'no_image/no_image.jpg';
            } else {
                $path = $image;
            }
            return $path;
        } else {
            if (empty($image)) {
                $path = $path = Yii::$app->params['httpurl'] . 'no_image/no_image.jpg';
            } else {
                if (file_exists(\realpath('../../') . '/uploads/' . $directory . '/' . $image)) {

                    $path = Yii::$app->params['httpurl'] . $directory . '/' . $image;
                } else {
                    $path = Yii::$app->params['httpurl'] . 'no_image/no_image.jpg';
                }
            }
            return $path;
        }
    }   
    
    public function file_not_found_hb($file, $directory) {

        if (substr($file, 0, 7) === "http://" || substr($file, 0, 8) === "https://") {

            if (empty($file)) {

                $path = "";
            } else {
                $path = $file;
            }
            return $path;
        } else {
            if (empty($file)) {
                $path = "";
            } else {
                if (file_exists(\realpath('../../') . '/uploads/' . $directory . '/' . $file)) {

                    $path = Yii::$app->params['httpurl'] . $directory . '/' . $file;
                } else {
                    $path = "";
                }
            }
            return $path;
        }
    }

    public function video_not_found_hb($image, $directory) {

        if (substr($image, 0, 7) === "http://" || substr($image, 0, 8) === "https://") {

            if (empty($image)) {

                $path = $path = Yii::$app->params['httpurl'] . 'no_image/no_video.jpg';
            } else {
                $path = $image;
            }
            return $path;
        } else {
            if (empty($image)) {

                $path = $path = Yii::$app->params['httpurl'] . 'no_image/no_video.jpg';
            } else {
                if (file_exists(\realpath('../../') . '/uploads/' . $directory . '/' . $image)) {
                    $path = Yii::$app->params['httpurl'] . $directory . '/' . $image;
                } else {
                    $path = '';
                }
            }
            return $path;
        }
    }

    public function image_not_found_profile($directory, $image) {

        if (empty($image)) {

            $path = $path = Yii::$app->params['httpurl'] . 'no_image/profile.jpg';
        } else {
            if (file_exists(\realpath('../../') . '/uploads/' . $directory . '/thumb/' . $image)) {

                $path = Yii::$app->params['httpurl'] . $directory . '/thumb/' . $image;
            } else {
                $path = Yii::$app->params['httpurl'] . 'no_image/profile.jpg';
            }
        }
        return $path;
    }
 public function image_not_found_api_main($directory,$image){
        
        if(empty($image)){
            
          //  $path = $path = Yii::$app->params['httpurl'].'no_image/no_image.jpg';
                $path = $path = Yii::$app->params['httpurl'] . 'no_image/no_image.jpg';
        }else
        {        
            if (file_exists(\realpath('../../').'/uploads/'.$directory.'/'.$image)) {
                
                $path = Yii::$app->params['httpurl'].$directory.'/'.$image;
            }else{
              // $path = Yii::$app->params['httpurl'].'no_image/no_image.jpg';
                $path =  Yii::$app->params['httpurl'] . 'no_image/no_image.jpg';
            }
            
        }
        return $path;
    }
    
    public function image_not_found_api_thumb($directory,$image){
        
        if(empty($image)){
            
            $path = Yii::$app->params['httpurl'].'no_image/no_image.jpg';
            
        }else
        {        
            if (file_exists(\realpath('../../').'/uploads/'.$directory.'/thumb/'.$image)) {
                
                $path = Yii::$app->params['httpurl'].$directory.'/thumb/'.$image;
            }else{
                  $path = Yii::$app->params['httpurl'].'no_image/no_image.jpg'; 
            }
            
        }
        return $path;
    }
    
    public static function date_format($date) {
        return date("d/m/Y",strtotime($date)+ (1 * 3600));
    }

    public static function front_date_format($date) {
         return date("d/m/Y H:i",strtotime($date)+ (1 * 3600));
    }
    
    
    public static function chat_time_format($date) {
        return date("H:i",strtotime($date)+ (1 * 3600));
    }
    
     public static function front_hourdifferent($date,$time_limit) {
        
           $asked_date = new \DateTime($date);
           $time_limit_interval = new \DateInterval('PT'.$time_limit.'H');
           $asked_date->add($time_limit_interval);
           
           $datetime1 = new \DateTime($asked_date->format('Y-m-d H:i:s')) ;
           $datetime2 = new \DateTime(date('Y-m-d H:i:s'));
           
           $interval = $datetime2->diff($datetime1);
           $hours = $interval->format('%H') + (($interval->format('%d'))*24);
           if($interval->format('%I') > 0){
               $hours = $hours + 1;
           }
           return $hours;
     }
     
     
     public static function oldfront_hourdifferent($date) {
            $time1 = strtotime($date);
            $time2 = strtotime(date('Y-m-d H:i:s'));
            $difference = round(abs($time2 - $time1) / 3600,2);
            return $difference;

     }
    
     
     public static function front_priceformat($price) {
            
         $price = '£'.number_format($price, 2);
         return $price;
     }
    
   

    public static function encrypt($string) {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = '123467890ABCDEFGHIJKLMNO';
        $secret_iv = 'This is my secret iv';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);


        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);



        return $output;
    }

    public static function decrypt($string) {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = '123467890ABCDEFGHIJKLMNO';
        $secret_iv = 'This is my secret iv';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);


        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);


        return $output;
    }

    public function sendMail($to, $sub, $message, $from = "") {

        $site_name = Yii::$app->name;
        $settings = \common\models\Setting::find(['status' => 1])->one();
        $logoimage = GeneralComponent::getlogo();
        if ($from == "") {
            $from = Yii::$app->params['sitename'];
        }
        $data = Yii::$app
                ->mailer
                ->compose(
                        ['html' => 'emailTemplate-html', 'text' => 'emailTemplate-text'], ['messegedata' => $message,'logoimage'=>$logoimage]
                )
                ->setFrom([$settings['setting_email'] => Yii::$app->name . ' Support'])
                ->setTo($to)
                ->setSubject($sub)
                ->send();

        return true;
    }


    public function saveMail($to, $sub, $message, $from = "") 
    {

        $site_name = Yii::$app->name;
        $settings = \common\models\Setting::find(['status' => 1])->one();
        $logoimage = GeneralComponent::getlogo();
        if ($from == "") {
            $from = Yii::$app->params['sitename'];
        }
        // $data = Yii::$app
        //         ->mailer
        //         ->compose(
        //                 ['html' => 'emailTemplate-html', 'text' => 'emailTemplate-text'], ['messegedata' => $message,'logoimage'=>$logoimage]
        //         )
        //         ->setFrom([$settings['setting_email'] => Yii::$app->name . ' Support'])
        //         ->setTo($to)
        //         ->setSubject($sub)
        //         ->send();

                
        $mail = array();
        $mail['to_email'] = $to;
        $mail['subject'] = $sub;
        $mail['message'] = $message;
        \Yii::$app->db->createCommand()->insert('mail_cron',$mail)->execute();
        return true;
    }
    
    /*
     * Get master list if id not passed else reurn field name
     * @param $table : master DB tabel
     * @param $id : specific one row 
     */

    public static function getMasterList($table, $id = '') {
        $data = [];
        if ($id != '') {
            $query = \Yii::$app->db
                    ->createCommand("SELECT name FROM $table WHERE " . $table . "_id=:id ");
            $query->bindValue(':id', $id);
            $model = $query->queryOne();
            if (!empty($model)) {
                $data = $model['name'];
            }
        } else {
            $query = \Yii::$app->db
                    ->createCommand("SELECT " . $table . "_id, name FROM $table WHERE status = 1 order by name ");
            $model = $query->queryAll();
            if (!empty($model)) {
                $data = \yii\helpers\ArrayHelper::map($model, $table . '_id', 'name');
            }
        }
        return $data;
    }

    /*
     * Get master list if id not passed else reurn field name
     * @param $table : master DB tabel
     * @param $id : specific one row 
     */

    public static function getCms($id) {
        $data = [];
        if ($id != '') {
            $model = \common\models\Cms::findOne($id);
            if (!empty($model)) {
                $data['title'] = $model['title'];
                $data['content'] = $model['content'];
            }
        }
        return $data;
    }
    
    public static function getlogo() {
        $logo = Yii::$app->params['httpurl'] . 'logo/coachcast_logo.png';
        return $logo;
    }
    
public static function left_sidebar_for_subadmin($menu_key){
        $left_array = 'SELECT * FROM user_menu_access WHERE user_id = '.\Yii::$app->user->identity->id.' AND status = 1';
        $left_array_data = Yii::$app->db->createCommand($left_array)->queryAll();
          $i = 0;
        foreach ($left_array_data as $left_array_value) {
                    $listarray[$i] = $left_array_value['menu_key'];
                    $i++;
                } 
        if (in_array($menu_key, $listarray)){return TRUE;}else{return  FALSE;}
    }    
public static function left_sidebar_for_subadmin_header($menu_key){
        $left_array = 'SELECT * FROM dynamic_menu WHERE menu_id = '.$menu_key.'  AND status = 1';
        $left_array_data = Yii::$app->db->createCommand($left_array)->queryAll();
        $menu_id_array =  yii\helpers\ArrayHelper::getColumn($left_array_data, 'id'); 
        if (empty($menu_id_array)){return FALSE;}else{return  TRUE;}
        $menu_id_string = rtrim(implode(',', $menu_id_array), ',');
       // print_r($left_array);exit;
        $user_access_query = 'SELECT * FROM user_menu_access WHERE user_id = '.\Yii::$app->user->identity->id.' 
                                                               AND menu_id IN ('.$menu_id_string.')AND status = 1';
        $return_data = Yii::$app->db->createCommand($user_access_query)->queryAll(); 
        if (empty($return_data)){return FALSE;}else{return  TRUE;}
    }
public static function left_sidebar_for_admin($menu_key){
        $left_array = 'SELECT * FROM dynamic_menu WHERE menu_id = '.$menu_key.'  AND status = 1';
        $left_array_data = Yii::$app->db->createCommand($left_array)->queryAll();
        $menu_id_array =  yii\helpers\ArrayHelper::getColumn($left_array_data, 'id'); 
        if (empty($menu_id_array)){return FALSE;}else{return  TRUE;}
    }    

    public static function get_controller_name($id){
        $menu_query = "SELECT * FROM auth_items WHERE auth_items_id = $id";                
        $menu_array = Yii::$app->db->createCommand($menu_query)->queryOne();
        $result = str_replace('Controller', "", $menu_array['auth_items_name']);
        $controllerdata = preg_replace('/\B([A-Z])/', '-$1', $result);
        $controller = strtolower($controllerdata);
        return $controller;
    }
    
    public static function get_file_type($filename){
        $extenstion = explode('.', $filename);
        $finalextenstion = strtolower($extenstion[1]);
        if (file_exists(\realpath('../../').'/frontend/web/img/doc-type/'. $finalextenstion.'_icon.png')) {
             $url = Yii::$app->params['url'].'img/doc-type/'.$finalextenstion.'_icon.png';
        }
        else
        {
             $url = Yii::$app->homeUrl.'img/doc-type/file-icon.png';
        }    
        return $url;
    }
    
}
