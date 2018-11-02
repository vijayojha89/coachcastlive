<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Home';
$this->params['breadcrumbs'][] = $this->title;
?>

  <!-- Start slider area  -->
  <div class="main-banner slider-top-space-header4">
            <img src="<?php echo Yii::$app->homeUrl . 'images/homebanner.jpg'; ?>" alt="image" class="img-responsive" />
            <div class="main-banner-inner">
                <div class="main-banner-top-title">Our Mission</div>
                <!--<h1><span>sweating in seconds</span> </h1>-->
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.Lorem Ipsum is simply
                    dummy text of the printing and typesetting industry.</p>

                <div class="button"><a href="#" class="btn custom-button" data-title="Join With Us">Join With Us</a></div>
            </div>

        </div>
        <!-- End slider area-->

        <!-- Start Our Program summer area -->
        <div class="our-program-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <h2 class="section-title-default2 title-bar-high2">ABOUT US</h2>
                        <p class="sub-title-default2">Lorem Ipsum is simply dummy text of the printing and typesetting
                            industry.Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <div class="program-box">
                                    <a href="#"><span class="flaticon-olympic-weightlifting"></span></a>
                                    <h3><a href="#">Cardio</a></h3>
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="program-box">
                                    <a href="#"><span class="flaticon-dumbbell"></span></a>
                                    <h3><a href="#">Spin Classes</a></h3>
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="program-box">
                                    <a href="#"><span class="flaticon-people"></span></a>
                                    <h3><a href="#">Strength Training</a></h3>
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="program-box">
                                    <a href="#"><span class="flaticon-sports"></span></a>
                                    <h3><a href="#">Performance Training</a></h3>
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 position-relative">
                        <div class="program-img">
                            <img src="<?php echo Yii::$app->homeUrl . 'img/being-builder.png'; ?>" class="img-responsive" alt="being-builder">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Our Program summer area -->


        <!-- Start Expert trainers area -->
        <div class="expert-trainer-area3 nav-on-hover">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h2 class="section-title-default title-bar-high">Find a Coach</h2>
                        <p class="sub-title-default">Lorem Ipsum is simply dummy text of the printing and typesetting
                            industry.Lorem Ipsum is simply dummy text of the printing and typesetting industry.Lorem
                            Ipsum is simply dummy text of the printing and typesetting industry.</p>
                        <div class="gym-carousel nav-control-middle" data-loop="true" data-items="3" data-margin="15"
                            data-autoplay="false" data-autoplay-timeout="10000" data-smart-speed="2000" data-dots="false"
                            data-nav="true" data-nav-speed="false" data-r-x-small="1" data-r-x-small-nav="true"
                            data-r-x-small-dots="false" data-r-x-medium="2" data-r-x-medium-nav="true"
                            data-r-x-medium-dots="false" data-r-small="3" data-r-small-nav="true" data-r-small-dots="false"
                            data-r-medium="4" data-r-medium-nav="true" data-r-medium-dots="false" data-r-large="4"
                            data-r-large-nav="true" data-r-large-dots="false">
                            <div class="trainer-box3">
                                <div class="trainer-box-wrapper">
                                    <div class="trainer-img-holder">
                                        <img src="<?php echo Yii::$app->homeUrl . 'images/trnee1.jpg'; ?>" class="img-responsive" alt="team">
                                        <div class="trainer-title-holder">
                                            <h3><a href="#">Amanda</a></h3>
                                            <div class="designation">AmandaDallas, Tx</div>
                                            <a class="choosebtn" href="#">Choose</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="trainer-box3">
                                <div class="trainer-box-wrapper">
                                    <div class="trainer-img-holder">
                                        <img src="<?php echo Yii::$app->homeUrl . 'images/trnee2.jpg'; ?>" class="img-responsive" alt="team">
                                        <div class="trainer-title-holder">
                                            <h3><a href="#">Amanda</a></h3>
                                            <div class="designation">AmandaDallas, Tx</div>
                                            <a class="choosebtn" href="#">Choose</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="trainer-box3">
                                <div class="trainer-box-wrapper">
                                    <div class="trainer-img-holder">
                                        <img src="<?php echo Yii::$app->homeUrl . 'images/trnee3.jpg'; ?>" class="img-responsive" alt="team">
                                        <div class="trainer-title-holder">
                                            <h3><a href="#">Amanda</a></h3>
                                            <div class="designation">AmandaDallas, Tx</div>
                                            <a class="choosebtn" href="#">Choose</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="trainer-box3">
                                <div class="trainer-box-wrapper">
                                    <div class="trainer-img-holder">
                                        <img src="<?php echo Yii::$app->homeUrl . 'images/trnee4.jpg'; ?>" class="img-responsive" alt="team">
                                        <div class="trainer-title-holder">
                                            <h3><a href="#">Amanda</a></h3>
                                            <div class="designation">AmandaDallas, Tx</div>
                                            <a class="choosebtn" href="#">Choose</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="trainer-box3">
                                <div class="trainer-box-wrapper">
                                    <div class="trainer-img-holder">
                                        <img src="<?php echo Yii::$app->homeUrl . 'images/trnee5.jpg'; ?>" class="img-responsive" alt="team">
                                        <div class="trainer-title-holder">
                                            <h3><a href="#">Amanda</a></h3>
                                            <div class="designation">AmandaDallas, Tx</div>
                                            <a class="choosebtn" href="#">Choose</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="trainer-box3">
                                <div class="trainer-box-wrapper">
                                    <div class="trainer-img-holder">
                                        <img src="<?php echo Yii::$app->homeUrl . 'images/trnee6.jpg'; ?>" class="img-responsive" alt="team">
                                        <div class="trainer-title-holder">
                                            <h3><a href="#">Amanda</a></h3>
                                            <div class="designation">AmandaDallas, Tx</div>
                                            <a class="choosebtn" href="#">Choose</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Expert tainers area -->

        <!-- Start being body builder area -->
        <div class="being-body-builder4">
            <div class="container">
                <div class="being-body-builder4-wrapper">
                    <div class="being-content">
                        <h2>GET TRAINING TODAY</h2>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                        <a class="custom-button2" href="#">JOIN WITH US</a>
                    </div>
                    <div class="being-right-img">
                        <img src="<?php echo Yii::$app->homeUrl . 'img/get-training.png'; ?>" alt="being-builder">
                    </div>
                </div>
            </div>
        </div>
        <!-- End being body builder area -->


        <!-- Start Expert trainers area -->
        <div class="expert-trainer-area3">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h2 class="section-title-default title-bar-high">EVERY FITNESS CLASS YOU CAN IMAGINE</h2>
                        <p class="sub-title-default">Lorem Ipsum is simply dummy text of the printing and typesetting
                            industry.Lorem Ipsum is simply dummy text of the printing and typesetting industry.Lorem
                            Ipsum is simply dummy text of the printing and typesetting industry.</p>

                         <div class="gym-carousel nav-control-middle" data-loop="true" data-items="3" data-margin="15"
                            data-autoplay="false" data-autoplay-timeout="10000" data-smart-speed="2000" data-dots="false"
                            data-nav="true" data-nav-speed="false" data-r-x-small="1" data-r-x-small-nav="true"
                            data-r-x-small-dots="false" data-r-x-medium="2" data-r-x-medium-nav="true"
                            data-r-x-medium-dots="false" data-r-small="3" data-r-small-nav="true" data-r-small-dots="false"
                            data-r-medium="4" data-r-medium-nav="true" data-r-medium-dots="false" data-r-large="4"
                            data-r-large-nav="true" data-r-large-dots="false">
                            <div class="trainer-box3">
                                <div class="trainer-box-wrapper">
                                    <div class="trainer-img-holder">
                                    <img src="<?php echo Yii::$app->homeUrl . 'images/barre.jpg'; ?>" class="img-responsive" alt="BARRE" />
                                    <div class="trainer-title-holder">
                                        <h3><a>BARRE</a></h3>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="trainer-box3">
                                <div class="trainer-box-wrapper">
                                    <div class="trainer-img-holder">
                                        <img src="<?php echo Yii::$app->homeUrl . 'images/pilates.jpg'; ?>" class="img-responsive" alt="team">
                                        <div class="trainer-title-holder">
                                            <h3><a href="#">PILATES</a></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="trainer-box3">
                                <div class="trainer-box-wrapper">
                                    <div class="trainer-img-holder">
                                        <img src="<?php echo Yii::$app->homeUrl . 'images/kickbox.jpg'; ?>" class="img-responsive" alt="team">
                                        <div class="trainer-title-holder">
                                            <h3><a href="#">CARDIO KICKBOX</a></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="trainer-box3">
                                <div class="trainer-box-wrapper">
                                    <div class="trainer-img-holder">
                                        <img src="<?php echo Yii::$app->homeUrl . 'images/hiit.jpg'; ?>" class="img-responsive" alt="team">
                                        <div class="trainer-title-holder">
                                            <h3><a href="#">HIIT</a></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="trainer-box3">
                                <div class="trainer-box-wrapper">
                                    <div class="trainer-img-holder">
                                        <img src="<?php echo Yii::$app->homeUrl . 'images/sports.jpg'; ?>" class="img-responsive" alt="team">
                                        <div class="trainer-title-holder">
                                            <h3><a href="#">Sports Conditioning</a></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="trainer-box3">
                                <div class="trainer-box-wrapper">
                                    <div class="trainer-img-holder">
                                        <img src="<?php echo Yii::$app->homeUrl . 'images/dance.jpg'; ?>" class="img-responsive" alt="team">
                                        <div class="trainer-title-holder">
                                            <h3><a href="#">DANCE (ETHNIC)</a></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

<div class="trainer-box3">
                                <div class="trainer-box-wrapper">
                                    <div class="trainer-img-holder">
                                        <img src="<?php echo Yii::$app->homeUrl . 'images/yoga.jpg'; ?>" class="img-responsive" alt="team">
                                        <div class="trainer-title-holder">
                                            <h3><a href="#">Yoga</a></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>


<div class="trainer-box3">
                                <div class="trainer-box-wrapper">
                                    <div class="trainer-img-holder">
                                        <img src="<?php echo Yii::$app->homeUrl . 'images/body_weight.jpg'; ?>" class="img-responsive" alt="team">
                                        <div class="trainer-title-holder">
                                            <h3><a href="#">Body Weight Sculpting</a></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>    
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- End Expert tainers area -->