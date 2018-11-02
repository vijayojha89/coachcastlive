<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
$line_item_detail = unserialize($model->zoho_line_items); 

if ($model->zoho_status == "paid" || $model->zoho_status == "sent") {
                $statustext = '<span style="color:green;">' . ucfirst($model->zoho_status) . '</span>';
            } else {
                $statustext = '<span style="color:red;">' . ucfirst($model->zoho_status) . '</span>';
            }

?>

    <h3>Details</h3>
    
    <div class="panel panel-default">
        <div class="table-responsive kv-grid-container">
            <table class="kv-grid-table table table-bordered table-striped kv-table-wrap">
                <tr>
                    <th>Student Name</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Transaction ID</th>
                    <th>Price</th>
                </tr>
                
                <?php if($line_item_detail){ 
                    
                      foreach ($line_item_detail as $lvalue) {
                          
                          $name = trim($lvalue->name);
                          $description = trim($lvalue->description);
                          if($name)
                          {
                              $studypad_txn_id = $name;
                          }
                          else
                          {
                               $studypad_txn_id = $description;
                          }    
                          
                          $transaction_detail = Yii::$app->db->createCommand("SELECT * FROM transaction WHERE studypad_txn_id = '$studypad_txn_id'")->queryOne();
                          if($transaction_detail)
                          {
                              $question_detail = \common\models\Question::findOne($transaction_detail['question_id']);
                              $user_detail = \common\models\User::findOne($question_detail['created_by']);
                              $qualification_data = \common\models\Qualification::findOne($question_detail['qualification_id']);
                              $subject_data = \common\models\Subject::findOne($question_detail['subject_id']);
                              
                          }  
                          
                          $studentname = (trim(@$user_detail['first_name'].' '.@$user_detail['last_name']))?trim(@$user_detail['first_name'].' '.@$user_detail['last_name']):'-';
                          $subjectname = (@$subject_data['name'])?@$subject_data['name']:'-';
                          $qualificationname = (@$qualification_data['name'])?@$qualification_data['name']:'-';
                          $date = (@$question_detail->asked_date)?\common\components\GeneralComponent::date_format(@$question_detail->asked_date):'-';
                          $transactionid = (@$transaction_detail['studypad_txn_id'])?$transaction_detail['studypad_txn_id']:'-';
                          $price = ($lvalue->item_total)?\common\components\GeneralComponent::front_priceformat($lvalue->item_total):'-';
                          ?>
                
                          <tr>
                                <td><?=$studentname;?><br/> <?=$qualificationname?> | <?=$subjectname?></td>
                                <td><?=$date;?></td>
                                <td><?=$statustext;?></td>
                                <td><?=$transactionid;?></td>
                                <td><?=$price;?></td>
                        </tr>
                          
                         <?php
                         
                          
                      }
                    
                    ?>
                
                <?php }else{ ?>
                        <tr>
                            <td colspan="5">No results found.</td>
                        </tr>
                   
                <?php } ?>       
            </table>
        </div>
         </div>
    