<?php

if(trim($_REQUEST['user_id']))
{    
    $encryption_key = common\components\GeneralComponent::decrypt(trim($_REQUEST['user_id']));
    if($encryption_key)
    {    
        $userallinvoice = Yii::$app->db->createCommand("SELECT * FROM invoice WHERE user_id = '$encryption_key'")->queryAll();
    }    
}

if(@$userallinvoice)
{
    
    $pagecount = 1;
    
    foreach ($userallinvoice as $value) { 
        
        
            $invoice_owner_detail = \common\models\User::findOne($value['user_id']);
        
        
        ?>
    
     <div style="height: 50px;">&nbsp;</div>   
    <div class="invoice-box">
        
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="<?php echo Yii::$app->homeUrl;?>/img/logo.png" style="width:100%; max-width:300px;">
                            </td>
                            
                            <td>
                                Invoice : <?php echo '#' . $value['zoho_invoice_number']; ?><br>
                                Date: <?php echo common\components\GeneralComponent::date_format($value['zoho_date']); ?><br>
                                Status:<?php echo ucfirst($value['zoho_status']);?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                Ludgate Hill<br/>
                                London,SW5<br/>
                                United Kingdom
                            </td>
                            
                            <td>
                                <?php echo @$invoice_owner_detail['first_name'].' '.@$invoice_owner_detail['last_name'];?><br>
                               <?php echo @$invoice_owner_detail['email'];?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <?php
            
            $line_item_detail = unserialize($value['zoho_line_items']); 
            
            
            ?>
            <tr>
                <td colspan="2">
                    <table>
                         <tr class="heading">
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
                                               <td><?php echo ucfirst($value['zoho_status']);?></td>
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
                    
                </td>
            </tr>
             
                        
            
            <tr class="total">
                <td></td>
                <td>
                   Total: <?php echo common\components\GeneralComponent::front_priceformat($value['zoho_total']); ?>
                </td>
            </tr>
            
        </table>
    </div>

    
    <?php if($pagecount != count($userallinvoice))
          {
                echo '<div style="page-break-after:always;">&nbsp;</div>';

          }   
     
    $pagecount++;
    
    }
    
}    
else
{
    echo 'No result found.';
}    
?>