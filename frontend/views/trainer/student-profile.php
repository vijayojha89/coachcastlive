<?php
//echo '<pre>';
//echo '......';print_r($reject_button);print_r($accept_button);
//echo '</pre>';
?>

<!-- Modal -->
    <!-- Modal content-->
  
    <div class="tutorprofile-header">
      <div class="user-info">
        <div class="col-sm-8 col-md-9">
          <div class="pic-box">
            <div class="profileimg">
              <div class="avatar-container p-100">
                <div class="userimg"><img src="<?php echo  $student_data['profile_photo_thumb'];?>" alt="Profile" width="" height=""></div>
              </div>
            </div>
          </div>
            
          <div class="info-box requested_profile_view">
            <h4><?php echo $student_data['first_name'].' '.$student_data['last_name'];?>
            </h4>
            <div class="sub-title"><?php echo $student_data['qualification'];?> | <span><?php echo $student_data['subjects'];?></span></div>
            <div class="profile_rating">
            <div class="rating_icon">
                                  <?php $userobj = new \common\models\User();
                                            $ratting = $userobj->usergetrating($student_data['student_id']);
                                            $totalrating = 5;
                                            $yellowrating = $ratting['avg_rating'];
                                            $greyrating = 5-$ratting['avg_rating'];
                                            if($yellowrating)
                                            {
                                                for($i = 1;$i<=$yellowrating;$i++){ ?>
                                                <img src="<?php echo Yii::$app->homeUrl;?>img/user-star-yellow.png" alt="" width="" height="">    
                                                <?php }
                                            }  
                                            if($greyrating)
                                            {
                                                for($i = 1;$i<=$greyrating;$i++){ ?>
                                                <img src="<?php echo Yii::$app->homeUrl;?>img/user-star-gray.png" alt="" width="" height="">    
                                                <?php }
                                            } 
                                          ?>
                                          
                                    <!--<div class="rating_user">(<?php //echo $ratting['no_of_user'];?>)</div>-->
            </div>
            </div>
          </div>
        </div>
        <div class="col-sm-4 col-md-3 text-right">
          <div class="close-right-box"> 
              <?php 
              if($reject_button == 1){
              ?>
              <a href="#"><img 
                      onclick="rejectQuestion(<?=$question_id?>,<?=\Yii::$app->user->identity->id?>)"
                      src="<?php echo Yii::$app->homeUrl;?>img/red-close.png" alt=""></a> 
              <?php 
              }
              if($accept_button == 1){
              ?>
              <a href="#"><img 
                      onclick="acceptQuestion(<?=$question_id?>,<?=\Yii::$app->user->identity->id?>,<?=$price_type?>)"
                      src="<?php echo Yii::$app->homeUrl;?>img/green-right.png" alt=""></a>
               <?php 
              }
              ?>
              <button type="button" class="close" data-dismiss="modal" id="displayclosebutton">&times;</button>
          </div>
        </div>
        <div class="cl"></div>
      </div>
    </div>
    <div class="fullprofilescroll">
      <div class="content mCustomScrollbar _mCS_1">
        <div class="tutorprofile-description">
        <?php if($student_data['bio'] != ''){ ?>
          <div class="tutorprofile-bio">
            <h3 class="maintitle">Bio</h3>
          <p><?= $student_data['bio'] ?></p>
        <?php } ?>
        </div>
        <div class="tutorprofile-review">
          <div class="reviewstar">
            <div class="whitestar"> <a href="javascript:void(0)"><img src="<?php echo Yii::$app->homeUrl;?>img/star_icon_white.png" alt="" width="" height=""></a> <a href="javascript:void(0)"><img src="<?php echo Yii::$app->homeUrl;?>img/star_icon_white.png" alt="" width="" height=""></a> <a href="javascript:void(0)"><img src="<?php echo Yii::$app->homeUrl;?>img/star_icon_white.png" alt="" width="" height=""></a> </div>
            <h3>Reviews</h3>
          </div>
          <?php
            if(!empty($student_data['student_reviews'])){
                foreach ($student_data['student_reviews'] as $review){
            ?>
                <div class="user-info">
                <div class="row">
                  <div class="col-sm-8 col-md-9">
                    <div class="pic-box"> <img src="<?= $review['profile_photo_thumb'];?>" alt=""></div>
                    <div class="info-box">
                      <h4><?php echo $review['first_name'].' '.$review['last_name'];?></h4>
                      <div class="sub-title"><!--High School |--> <span><?= $review['subjects'];?></span></div>
                      <div class="gray-text"><?= $review['comment'];?></div>
                    </div>
                  </div>
                  <div class="col-sm-4 col-md-3 text-right">
                    <div class="rating-ic">
                                  <?php 
                                            $totalrating = 5;
                                            $yellowrating = $review['student_rating'];
                                            $greyrating = 5-$review['student_rating'];
                                            if($yellowrating)
                                            {
                                                for($i = 1;$i<=$yellowrating;$i++){ ?>
                                                <img src="<?php echo Yii::$app->homeUrl;?>img/user-star-yellow.png" alt="" width="" height="">    
                                                <?php }
                                            }  
                                            if($greyrating)
                                            {
                                                for($i = 1;$i<=$greyrating;$i++){ ?>
                                                <img src="<?php echo Yii::$app->homeUrl;?>img/user-star-gray.png" alt="" width="" height="">    
                                                <?php }
                                            }  
                                          ?>
                              </div>
                  </div>
                </div>
               </div>
            <?php
                }
            }
            else{
                echo 'No Reviews.';
            }
            ?>
        <div class="cl"></div>
      </div>
    </div>
<script type="text/javascript">
</script> 
