<?php
use yii\helpers\Html;
use common\models\User;
use common\models\Question;
use yii\helpers\Url;
//echo \Yii::$app->user->identity->user_last_login;
$this->title = 'Dashboard';
$students = User::find()->where(['role'=>'student'])->andWhere(['<>', 'status', 2])->count();
$tutors = User::find()->where(['role'=>'tutor'])->andWhere(['<>', 'status', 2])->count();
$question = Question::find()->Where(['<>', 'status', 2])->count();;

?>
<?php
    yii\bootstrap\Modal::begin(['id' =>'modal',
                                'closeButton' => [
                                'label' => 'Close',
                                'class' => 'btn btn-danger btn-sm pull-right',
                                ],
                               'size' => 'modal-lg',]);
    yii\bootstrap\Modal::end();
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
<div class="row">
        
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-graduation-cap"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Students Registered</span>
              <span class="info-box-number"><?= $students ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Tutor Registered</span>
              <span class="info-box-number"><?= $tutors ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-question-circle "></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Questions <br/>asked</span>
              <span class="info-box-number"><?= $question ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>

<section class="">
    <h1 style="font-size:24px;margin-bottom: 20px;">
                Invoices            </h1>
        
            </section>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="pull-right">
            <a href="<?php echo Url::to(['invoice/index']);?>" class="btn btn-primary">View All</a>
    </div>
    <h3 class="panel-title">
        Last 5 Invoices
    </h3>
    <div class="clearfix"></div></div>
    
    <table class="kv-grid-table table table-bordered table-striped kv-table-wrap">
        <tr>
            <th>Invoice ID</td>
            <th>Status</td>
            <th>Date</td>
            <th>Price</td>   
            <th></th>
        </tr>
        <?php
        
        $userallinvoice = Yii::$app->db->createCommand("SELECT * FROM invoice WHERE user_id != 0 ORDER BY invoice_id DESC LIMIT 5")->queryAll();

        if($userallinvoice)
        {   
            foreach ($userallinvoice as $value) {
                
               echo '<tr>
                        <td>#'.$value['zoho_invoice_number'].'</td>
                        <td>'.ucfirst($value['zoho_status']).'</td>
                        <td>'.common\components\GeneralComponent::date_format($value['zoho_date']).'</td>
                        <td>'.common\components\GeneralComponent::front_priceformat($value['zoho_total']).'</td>
                        <td><a href="'.$value['zoho_invoice_url'].'" target="_blank" class="btn btn-warning">View Invoice Url</a></td>
                    </tr>';
                
            }
           
            
        }
        else
        {
            echo '<tr><td colspan="5">No result found.</td></tr>';
        }    
        ?>
        
        
        
    </table>   
        
        

      </div>
<?php

$this->registerJs(
        
        ' 

         $.ajax({
                url  : "site/first-login",
                success  : function(data) {
                
                 $("#modal").modal("show").find(".modal-content").html(data);
                 var span = document.getElementsByClassName("close")[0];
                            span.onclick = function() {
                            $("#modal").modal("hide");
                                                       }
                                }
                
                });
 
            ', \yii\web\VIEW::POS_END); 
?>
<style>
    .info_big{
        height: 430px;
        line-height: 430px;
        text-align: center;
    }
</style>