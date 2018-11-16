<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\TrainerClass */

$this->title = 'Add New Class';

?>


<!-- Start Inner Banner area -->
<div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2>Add New Class</h2>
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
                            <h2 class="section-title-default2 title-bar-high2">Add New Class</h2>
                            <?=
                                $this->render('_form', [
                                    'model' => $model,
                                ])
                                ?>

                        </div>
                    </div>
                </div>
            </div>
</div>