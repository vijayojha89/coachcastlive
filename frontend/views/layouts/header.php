<?php

use yii\helpers\Url;
use yii\helpers\Html;

$gnl = new \common\components\GeneralComponent();
$mnl = new common\components\MasterComponent();
$unread_notifi_count = $mnl->unread_notifications(@Yii::$app->user->id);
?>
 <!-- Start Header area -->
 <header class="main-header header-style4" id="sticker">
            <div class="header-top-bar" style="margin-bottom: 0px;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="top-bar-left">
                                <ul>
                                    <li><i class="fa fa-phone" aria-hidden="true"></i><a href="Tel:+1234567890"> + 123
                                            456 78910 </a></li>
                                    <li><i class="fa fa-envelope" aria-hidden="true"></i><a href="#">info@coachcastlive.com</a></li>
                                </ul>
                            </div>
                        </div>

                        <?php if (!Yii::$app->user->isGuest) { ?>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="header-top-right">
                                <ul>
                                    <!--<li>
                                        <div class="header-top-search search-box">
                                            <form>
                                                <input class="search-text" type="text" placeholder="Search Here...">
                                                <a class="search-button" href="#">
                                                    <i class="fa fa-search" aria-hidden="true"></i>
                                                </a>
                                            </form>
                                        </div>
                                    </li>-->
                                    <li>
                                        <a href="<?php echo Url::to(['user/notifications']); ?>" class="cart-area floatright">
                                            <i class="fa fa-bell-o"></i>
                                            <?php if ($unread_notifi_count > 0) { ?>
                                                <span><?= $unread_notifi_count ?></span>
                                            <?php } ?>
                                          
                                        </a>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="header-top-area">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="logo-area">
                                <a href="<?php echo Url::to(['site/index']); ?>"><img src="<?= Yii::$app->params['httpurl'] . 'logo/coachcast_logo.png'; ?>"  alt="logo"></a>
                            </div>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-10">
                            <div class="main-menu">
                                <nav>
                                    <ul>
                                    <?php if (Yii::$app->user->isGuest) { ?>
                                    <li class="<?php if (Yii::$app->controller->id == 'site' AND Yii::$app->controller->action->id == 'index' ) { ?>active<?php } ?>">
                                        <a href="<?php echo Url::to(['site/index']); ?>">Home</a>
                                    </li>
                                    <li class="<?php if (Yii::$app->controller->action->id == 'about' ) { ?>active<?php } ?>"><a href="<?php echo Url::to(['site/about']); ?>">About Us</a></li>
                                    <li class="<?php if (Yii::$app->controller->action->id == 'howitworks' ) { ?>active<?php } ?>"><a href="<?php echo Url::to(['site/howitworks']); ?>">How It Works</a></li>
                                    <li class="<?php if (Yii::$app->controller->action->id == 'contact' ) { ?>active<?php } ?>"><a href="<?php echo Url::to(['site/contact']); ?>">Contact Us</a></li>
                                    <li class="<?php if (Yii::$app->controller->action->id == 'email-login' ) { ?>active<?php } ?>"><a href="<?php echo Url::to(['site/email-login']); ?>">Login</a></li>
                                    <li class="<?php if (Yii::$app->controller->action->id == 'signup' ) { ?>active<?php } ?>"><a href="<?php echo Url::to(['user/signup']); ?>">Sign Up</a></li>   

                                <?php } else { ?>
                                    <li><a href="<?php echo Url::to(['site/index']); ?>">Dashboard</a></li>
                                    <li><a href="<?php echo Url::to(['site/logout']); ?>">Log Out</a></li>

                                <?php } ?>  
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End header Top Area -->
            <!-- mobile-menu-area start -->
            <div class="mobile-menu-area">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mobile-menu">
                                <nav id="dropdown">
                                    <ul>

                                    <?php if (Yii::$app->user->isGuest) { ?>
                                    <li class="<?php if (Yii::$app->controller->id == 'site' AND Yii::$app->controller->action->id == 'index' ) { ?>active<?php } ?>">
                                        <a href="<?php echo Url::to(['site/index']); ?>">Home</a>
                                    </li>
                                    <li><a href="<?php echo Url::to(['site/about']); ?>">About Us</a></li>
                                    <li><a href="<?php echo Url::to(['site/howitworks']); ?>">How It Works</a></li>
                                    <li><a href="<?php echo Url::to(['site/contact']); ?>">Contact Us</a></li>
                                    <li><a href="<?php echo Url::to(['site/login']); ?>">Login</a></li>
                                    <li><a href="<?php echo Url::to(['site/signup']); ?>">Sign Up</a></li>   

                                <?php } else { ?>
                                    <li><a href="<?php echo Url::to(['site/index']); ?>">Dashboard</a></li>
                                    <li><a href="<?php echo Url::to(['site/logout']); ?>">Log Out</a></li>

                                <?php } ?>  

                                        

                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- mobile-menu-area end -->
        </header>
        <!-- End Header area -->

<!--<header id="header"> 
    <nav class="navbar navbar-default navbar-color-on-scroll navbar-transparent" role="navigation">
        <div class="container">
 
            <?php
            if (!Yii::$app->user->isGuest) {

                $userdata = common\models\User::findOne(Yii::$app->user->id);


                $gnl = new \common\components\GeneralComponent();

                if ($userdata->role == "student") {
                    ?>
                    <div class="profile_li">
                        <div class="dropdown"> <a id="profile" class="dropdown-toggle" role="button" data-toggle="dropdown" data-target="#" href="javascript:void(0)"><span><img src="<?php echo $gnl->image_not_found_hb($userdata['profile_photo'], 'profile_photo'); ?>" alt="" width="" height=""></span></a>
                            <ul class="dropdown-menu headerprofile" role="menu" aria-labelledby="dLabel">
                                <li><a href="<?php echo Url::to(['site/logout']); ?>">Sign Out</a></li>
                            </ul>
                        </div>
                    </div>

                <?php } else { ?>

                    <div class="profile_li">
                        <div class="dropdown">
                            <a id="profile" class="dropdown-toggle" role="button" data-toggle="dropdown" data-target="#" href="javascript:void(0)">
                                <span><img src="<?php echo $gnl->image_not_found_hb($userdata['profile_photo'], 'profile_photo', 1); ?>" alt="" width="" height=""></span>
                            </a>
                            <ul class="dropdown-menu headerprofile" role="menu" aria-labelledby="dLabel">
                                <li class="<?php if (Yii::$app->controller->id == 'trainer-video' AND Yii::$app->controller->action->id != 'transations' AND Yii::$app->controller->action->id != 'schedule') { ?>active<?php } ?>"><a href="<?php echo Url::to(['trainer-video/index']); ?>">Videos</a></li>
                                <li class="<?php if (Yii::$app->controller->id == 'blog') { ?>active<?php } ?>"><a href="<?php echo Url::to(['blog/index']); ?>">Blogs</a></li>
                                <li class="<?php if (Yii::$app->controller->id == 'trainer-class') { ?>active<?php } ?>"><a href="<?php echo Url::to(['trainer-class/index']); ?>">Classes</a></li>
                                <li class="<?php if (Yii::$app->controller->action->id == 'profile') { ?>active<?php } ?>"><a  href="<?php echo Url::to(['trainer/profile']); ?>">Profile</a></li>
                                <li class="<?php if (Yii::$app->controller->action->id == 'schedule') { ?>active<?php } ?>"><a href="<?php echo Url::to(['trainer-video/schedule']); ?>">Schedules</a></li>
                                <li class="<?php if (Yii::$app->controller->action->id == 'transations') { ?>active<?php } ?>"><a  href="<?php echo Url::to(['trainer-video/transations']); ?>">Transactions</a></li>
                            </ul>
                        </div>
                    </div>




                <?php } ?>

                <div class="notifications_li">
                    <div class="dropdown">
                        <a href="<?php echo Url::to(['user/notifications']); ?>">
                            <i class="fa fa-bell" style="font-size:25px;">
                                <?php if ($unread_notifi_count > 0) { ?>
                                       <span class="badge badge-danger"><?= $unread_notifi_count ?></span>
                                <?php } ?>
                                
                            </i>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </nav>
</header>-->