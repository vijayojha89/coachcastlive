<?php
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\time\TimePicker;
$this->title = "Availability";
?>
<!-- Start Inner Banner area -->
<div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>Availability</h2>
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
                            <h2 class="section-title-default2 title-bar-high2">Availability</h2>
                            <form method="post" action="<?php Url::to['trainer/coachcastlive'];?>">
                            
                            
                            <div class="panel panel-default">
                                <div class="panel-heading">Month Availability</div>
                                <div class="panel-body">
                               <?php
                                 $monthAvailability = unserialize($availabilityDetail['month_availability']);
                               for ($m=date('m'); $m<=12; $m++) {
                                    $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
                                    echo '<label class="checkbox-inline"><input type="checkbox" name="monthselected[]" value="'.$m.'">'.$month.'</label>';
                                } ?>
                                
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading">Days Availability</div>
                                <div class="panel-body">
                              
                                 <?php 
                                 $days = [
                                   
                                    'Monday',
                                    'Tuesday',
                                    'Wednesday',
                                    'Thursday',
                                    'Friday',
                                    'Saturday',
                                    'Sunday',
                                ];
                                 foreach($days as $key => $value){
                                    echo '<label class="checkbox-inline"><input type="checkbox" value="'.($key+1).'">'.$value.'</label>'; 
                                 }
                                 ?>

                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading">Times Available For Calls</div>
                                <div class="panel-body">
                                
                               
                                    
                                    <div class = "form-inline">
                                    <div class = "form-group">
                                    <?php echo '<label>From</label>';
                                        echo TimePicker::widget([
                                            'name' => 'start_time', 
                                            'value' => '11:24 AM',
                                            'pluginOptions' => [
                                                'showSeconds' => false
                                            ]
                                        ]); 
                                    ?>    
                                    </div>
                                    <div class = "form-group">
                                    <?php echo '<label>To</label>';
                                        echo TimePicker::widget([
                                            'name' => 'start_time', 
                                            'value' => '11:24 AM',
                                            'pluginOptions' => [
                                                'showSeconds' => false
                                            ]
                                        ]); 
                                    ?>    
                                    </div>
                                    </div>

                                    <div class = "form-inline">
                                    <div class = "form-group">
                                    <?php echo '<label>From</label>';
                                        echo TimePicker::widget([
                                            'name' => 'start_time', 
                                            'value' => '11:24 AM',
                                            'pluginOptions' => [
                                                'showSeconds' => false
                                            ]
                                        ]); 
                                    ?>    
                                    </div>
                                    <div class = "form-group">
                                    <?php echo '<label>To</label>';
                                        echo TimePicker::widget([
                                            'name' => 'start_time', 
                                            'value' => '11:24 AM',
                                            'pluginOptions' => [
                                                'showSeconds' => false
                                            ]
                                        ]); 
                                    ?>    
                                    </div>
                                    </div>


                                </div>
                            </div>
                                <button type="submit" class="btn btn-default">Cancel</button>                   
                                <button type="submit" class="btn btn-info">Save</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
 </div>               
