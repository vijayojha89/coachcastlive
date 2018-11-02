<?php

use yii\helpers\Url;
use yii\helpers\Html;

$gnl = new \common\components\GeneralComponent();
?>
<?= $this->render('/site/needhelp') ?>
<div id="myModal1" class="modal fade popupwidth " role="dialog"></div>
<div id="myModal2" class="modal fade popupwidth " role="dialog"></div>
<div id="myModal3" class="modal fade popupwidth " role="dialog"></div>
<div id="myModal4" class="modal fade popupwidth " role="dialog"></div>
<div id="pwdModal" class="modal fade popupwidth " role="dialog"></div>

<!-- Forgot pass -->
<div id="myModal5" class="modal fade popupwidth " role="dialog"></div>
<header id="header"> 

    <!-- ============================== --> 
    <!-- Header Logo & Menu --> 
    <!-- ============================== -->
    <nav class="navbar navbar-default navbar-transparent navbar-fixed-top navbar-color-on-scroll" id="sectionsNav">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                <a class="navbar-brand" href="<?php echo Url::to(['site/index']); ?>"><img src="<?= Yii::$app->params['httpurl'] . 'setting_logo_image/' . $settings->setting_logo_image ?>" alt="FitMakersLive" width="" height=""></a> </div>
            <div class="collapse navbar-collapse" id="navigation-example">
                <ul class="nav navbar-nav navbar-right">
                    <li class="howitworks"> <a href="#howitwork">How It Works</a> </li>
                    <li>
                        <?php if (Yii::$app->user->isGuest) { ?>
                            <a data-toggle="modal" onclick="hideOverflowHidden()" data-target="#myModal1" href="javascript:void(0)" class="btn btn-rose btn-round" id="btn_signin">Sign In</a> 
                        <?php } else { ?> 
                            <a href="<?= Url::base(true) . '/site/logout' ?>" class="btn btn-rose btn-round">Log Out</a> 
                        <?php } ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>    
</header>