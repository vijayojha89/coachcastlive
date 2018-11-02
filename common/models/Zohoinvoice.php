<?php

namespace common\models;

use Yii;

class Zohoinvoice extends \yii\db\ActiveRecord
{
    
    private function urlencode_array($array) {
        foreach($array as $key => $row) {
            if (is_array($row)) {
                $array[$key] = $this->urlencode_array($row);
            } else {
                if (!is_bool($row)) {
                    $array[$key] = urlencode($row);
                }
            }
        }

        return $array;
    }
    
    
    protected function sendRequest($url, $data, $type = 'POST') {
        $jsonData = json_encode($this->urlencode_array($data));
        if ($type == 'POST')
        {
            $ch = curl_init("https://invoice.zoho.com/api/v3/{$url}?authtoken=".Yii::$app->params['ZOHO_AUTH_TOKEN']."&organization_id=".Yii::$app->params['ZOHO_ORGANIZATION_ID']."&JSONString={$jsonData}");
            curl_setopt($ch, CURLOPT_VERBOSE, 1);//standard i/o streams
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);// Turn off the server and peer verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);//Set to return data to string ($response)
            curl_setopt($ch, CURLOPT_POST, TRUE);//Regular post
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json") );
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        }
        else
        {
            $ch = curl_init("https://invoice.zoho.com/api/v3/{$url}?authtoken=".Yii::$app->params['ZOHO_AUTH_TOKEN']."&organization_id=".Yii::$app->params['ZOHO_ORGANIZATION_ID']."&JSONString={$jsonData}");
            curl_setopt($ch, CURLOPT_VERBOSE, 1);//standard i/o streams
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);// Turn off the server and peer verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);//Set to return data to string ($response)
            curl_setopt($ch, CURLOPT_POST, FALSE);//Regular post
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json") );
        }   

        $result = curl_exec($ch);
        if (curl_error($ch)) 
        {
            $result = array();
        }
        else
        {    
            $result = json_decode($result);
            if (is_object($result) === false || $result->code != 0)
            {
                $result = array();
            }
        }
        curl_close($ch);
        
        return $result;
    }
    
    
    public function getInvoicePdf($invoiceId, $type = 'pdf') {
        $fields = array('accept' => $type);
        $response = $this->sendRequest("invoices/{$invoiceId}", $fields, 'GET');
        return @$response->invoice;
    }
    
    
    public function zoho_create_contact($user_id)
    {
        $userdata = User::findOne($user_id);
        if($userdata['zoho_contact_id'] == "")
        {
            $fields = ['contact_name'=>$userdata['first_name'].' '.$userdata['last_name'],'email'=>$userdata['email']];
            $response = $this->sendRequest('contacts', $fields, 'POST');
            if($response)
            {
                $zoho_contact_id = $response->contact->contact_id;
                Yii::$app->db->createCommand()->update('user', ['zoho_contact_id' => $zoho_contact_id], 'id=' . $userdata['id'])->execute();
            }    
        }
        
        return true;
    }
    
    public function zoho_get_invoice($studypad_txn_id)
    {
        $fields = array('accept' => 'pdf','reference_number'=>$studypad_txn_id);
        $jsonData = json_encode($this->urlencode_array($fields));
        
        $query_array = array(
                                'authtoken'=>Yii::$app->params['ZOHO_AUTH_TOKEN'],
                                'organization_id'=>Yii::$app->params['ZOHO_ORGANIZATION_ID'],
                                'reference_number'=>$studypad_txn_id,
                                'JSONString'=>$jsonData
                             );
        
        $ch = curl_init("https://invoice.zoho.com/api/v3/invoices/?".http_build_query($query_array));
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json") );
            
        $result = curl_exec($ch);
         if (curl_error($ch)) 
         {
             $result = array();
         }
         else
         {    
             $result = json_decode($result);
             if (is_object($result) === false || $result->code != 0)
             {
                 $result = array();
             }
         }
         curl_close($ch);

         return @$result->invoices;
    }        
}

