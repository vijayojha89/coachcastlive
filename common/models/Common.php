<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\components\GeneralComponent;
/**
 * This is the model class for table "category".
 *
 * @property integer $category_id
 * @property string $category_name
 * @property integer $status
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 */
class Common extends \yii\db\ActiveRecord
{
    public static function fav_tutor($student_id)
    {
         $gnl = new GeneralComponent();
         if($student_id){
         $tutor_array = ArrayHelper::getColumn(
                        yii::$app->db->createCommand('SELECT * FROM fav_tutor 
                                         LEFT JOIN `user` 
                                         ON fav_tutor.student_id = user.id 
                                         WHERE fav_tutor.student_id = '.$student_id.'AND user.show_fav_tutor = 1 ')
                                         ->queryAll()
                   , 'tutor_id');
         if(!empty($tutor_array)){
             foreach ($tutor_array as $tutor_id) {
                 $tutor = User::findOne(['id'=>$tutor_id,'role'=>'tutor','status'=>1]);
                 if($tutor){
                   $data[] = [
                             'id'=>(int) $tutor['id'],
                             'first_name'=>(string) $tutor['first_name'],
                             'last_name'=>(string) $tutor['last_name'],
                             'email'=>(string) $tutor['email'],
                             'profile_photo'=>(string) $gnl->image_not_found_api_main('profile_photo', $tutor['profile_photo']),
                             'profile_photo_thumb'=>(string) $gnl->image_not_found_api_thumb('profile_photo', $tutor['profile_photo']),
                           ];  
                 }
                 
             }
             return $data;
         }
         else{
             return [];
         }
         }
         else{
             return [];
         }
       
    }
    
    public static function recommended_tutor($student_id)
    {
         $gnl = new GeneralComponent();
         if($student_id){
         $tutor_array = ArrayHelper::getColumn(User::findAll(['role'=>'tutor','status'=>1]), 'id');
         if(!empty($tutor_array)){
             foreach ($tutor_array as $tutor_id) {
                 $tutor = User::findOne(['id'=>$tutor_id,'role'=>'tutor','status'=>1]);
                 if($tutor){
                   $data[] = [
                             'id'=>(int) $tutor['id'],
                             'first_name'=>(string) $tutor['first_name'],
                             'last_name'=>(string) $tutor['last_name'],
                             'email'=>(string) $tutor['email'],
                             'profile_photo'=>(string) $gnl->image_not_found_api_main('profile_photo', $tutor['profile_photo']),
                             'profile_photo_thumb'=>(string) $gnl->image_not_found_api_thumb('profile_photo', $tutor['profile_photo']),
                           ];  
                 }
                 
             }
             return $data;
         }
         else{
             return [];
         }
         }
         else{
             return [];
         }
       
    }
    
 /*
  * get address from lat,long
  */
public static function getAddress($lat,$lng) {
    $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
     $json = @file_get_contents($url);
     $data=json_decode($json);
     $status = $data->status;
     if($status=="OK")
     {
       return $data->results[0]->formatted_address;
     }
     else
     {
       return false;
     }
}    
}
