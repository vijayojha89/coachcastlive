<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
$mnl = new \common\components\MasterComponent();
$question_status = $mnl->question_detail($model->question_id, $model->created_by)['questions'][0];
//echo '<pre>';
//print_r($question_status);
//echo '</pre>';
?>
<div class="question_expand">
    <h3>Question Details</h3>
    <div class="row">
        <div class="description">
            <div class="description_text">
    <div class="col-lg-12       ">   
    <h4>Title</h4>
    <p>
        <?= $model->title ?>
    </p>
    </div>
     </div>
    <?php if($model->description != ''){?>
     <div class="description_text">
    <div class="col-lg-12       ">   
    <h4>Description</h4>
    <p>
        <?= $model->description ?>
    </p>
    </div>
     </div>
    <?php } ?>
        
        
    <?php if(!empty($question_status['documents'])){?>
      <div class="description_file">
    <div class="col-lg-12">
    <?php 
            foreach ($question_status['documents'] as $document) {
    ?>       
                <a target="_blank" data-pjax="false" href="<?= $document['document_name'] ?>">
                    <img src="<?=\common\components\GeneralComponent::get_file_type($document['original_name'])?>" alt="">
                    <?= $document['original_name']?>  
                </a> 
    <?php
            }
    ?>
    </div>
      </div>
    <?php } ?>
    </div>   
    <?php if($model->answer_id != 0){?>
         <div class="description">
    <div class="col-lg-12">   
    <h4>Answer</h4>
        <?php
        if($question_status['answer']['answer_type'] == 0){ echo '<p>'.$question_status['answer']['answer_text'].'</p>';}
        if($question_status['answer']['answer_type'] == 1){
            ?>
    <div>
                <a target="_blank" data-pjax="false" href="<?= $question_status['answer']['file_name'] ?>">
                    <img src="<?=\common\components\GeneralComponent::get_file_type($question_status['answer']['file_name_original'])?>" alt="">
                    <?= $question_status['answer']['file_name_original']?>  
                </a>   
    </div>
            <?php
        }
        ?>
    </div>
         </div>
    <?php } ?>
       
        <div class="col-lg-12">
            <table class="table table-bordered table-condensed table-hover small kv-table">
                <?php
                    if($model->price_type == 1){
                ?>
                <tr>
                    <td>Price</td><td class="text-right">£ <?= $model->price ?></td>
                </tr>
                <?php
                    }
                    else if($model->price_type == 2){
                ?>
                <tr>
                    <td>Minimum Price</td><td class="text-right">£ <?= $model->min_budget ?></td>
                </tr>
                <tr>
                    <td>Maximum Price</td><td class="text-right">£ <?= $model->max_budget ?></td>
                </tr>
                <?php
                }
                ?>
               
                <?php
                if($model->question_status == 1 && $model->confirm_select_tutor != 0 && $model->price_type == 2){
                    ?>
                <tr>
                    <td>Confirmed Bid</td><td class="text-right">£ <?= $model->confirm_bid ?></td>
                </tr>  
                <?php       
                } 
                if($model->question_status == 2){
                ?>
                <tr>
                    <td>Paid Amount</td><td class="text-right">£ <?= $question_status['payment_amount'] ?></td>
                </tr>
                <tr>
                    <td>Payment Type</td><td class="text-right"><?= $question_status['payment_type'] ?></td>
                </tr>
                <?php
                }
                ?>
               
                <?php
                    if($model->confirm_select_tutor == 0){
                    $invite_tutor_detail = \common\models\InvitedTutor::findAll(['question_id'=>$model->question_id]);
                     $i = 0;
                    foreach ($invite_tutor_detail as $value) {
                        $listarray[$i] = implode(' ', ArrayHelper::map(common\models\User::find()
                                                   ->select(new \yii\db\Expression("CONCAT(`first_name`, '  ', `last_name`) as name"))
                                                   ->where(['id' => $value['tutor_id']])->asArray()->all(), 'id', 'name'));
                        $i++;
                                }
                    $tutors= rtrim(implode(',', $listarray), ',');
                ?>
                <tr>
                    <td>Invited Tutors</td>
                    <td class="text-right">
                        <?= $tutors ?>
                    </td>
                </tr>
                <?php
                    }
                    else {
                ?>
                <tr>
                    <td>Selected Tutor</td>
                    <td class="text-right">
                        <?= implode(' ', ArrayHelper::map(common\models\User::find()
                                               ->select(new \yii\db\Expression("CONCAT(`first_name`, '  ', `last_name`) as name"))
                                               ->where(['id' => $model->confirm_select_tutor])->asArray()->all(), 'id', 'name')) ?>
                    </td>
                </tr>
                <?php       
                    }
                ?>
            </tbody></table>
        </div>
    </div>
</div>