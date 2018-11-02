<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

//print_r(Yii::$app->user->identity);

$gnrl = new common\components\GeneralComponent();


//print_r($settings->setting_logo_image);
?>

<header class="main-header">
                                <!--<img src="<?php echo Yii::$app->params['httpurl'] . 'logo/large-logo.png' ?>" alt="" />
                                <img src="<?php echo Yii::$app->params['httpurl'] . 'logo/small-logo.png' ?>" alt="" />-->
    <?=
    Html::a('<span class="logo-mini"><img src="' . Yii::$app->params['httpurl'] . 'setting_favicon_image/' . $settings->setting_favicon_image . '" alt="" /></span><span class="logo-lg">'
            . '<img src="' . Yii::$app->params['httpurl'] . 'setting_logo_image/' . $settings->setting_logo_image . '" alt="" width="90" /></span>', Yii::$app->homeUrl, ['class' => 'logo'])
    ?>
    <nav class="navbar navbar-static-top" role="navigation"> <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <span class="sr-only">Toggle navigation</span> </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img src="<?php echo $gnrl->image_not_found_profile('profile_photo', Yii::$app->user->identity->profile_photo); ?>" class="user-image" alt="User Image"/> <span class="hidden-xs"><?php echo Yii::$app->user->identity->first_name . ' ' . Yii::$app->user->identity->last_name ?></span> </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
<!--                        <li class="user-header"> <img src="<?php echo $gnrl->image_not_found_profile('profile_photo', Yii::$app->user->identity->profile_photo); ?>" class="img-circle"
                                                      alt="User Image"/>
                            <p> <?php echo Yii::$app->user->identity->first_name . ' ' . Yii::$app->user->identity->last_name ?>  </p>
                        </li>

                         Menu Footer
                        <li class="user-footer">
                            <div class="pull-left"> <?php
                        echo Html::a(
                                'Profile', ['/user/update-profile?id=' . common\components\GeneralComponent::encrypt(Yii::$app->user->identity->id)], ['class' => 'btn btn-default btn-flat']
                        )
                        ?> </div>
                            <div class="pull-left"> <?php
                        echo Html::a(
                                'Change Password', ['/user/change-password?id=' . common\components\GeneralComponent::encrypt(Yii::$app->user->identity->id)], ['class' => 'btn btn-default btn-flat']
                        )
                        ?> </div>
                            <div class="pull-right">
                        <?=
                        Html::a(
                                'Sign out', ['/site/logout'], ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                        )
                        ?>
                            </div>
                        </li>-->

                        <!-- Menu Footer-->
                        <li>
                            <?php
                            echo Html::a(
                                    '<i class="fa fa-user"></i> Profile', ['/user/update-profile?id=' . common\components\GeneralComponent::encrypt(Yii::$app->user->identity->id)], ['class' => 'btn btn-default btn-flat']
                            )
                            ?> 
                        </li>
                        <li>
                           <?php
                        echo Html::a(
                                '<i class="fa fa-key"></i> Change Password', ['/user/change-password?id=' . common\components\GeneralComponent::encrypt(Yii::$app->user->identity->id)], ['class' => 'btn btn-default btn-flat']
                        )
                        ?>
                        </li>
                        <li>
                            <?=
                            Html::a(
                                    '<i class="fa fa-sign-out"></i>Sign out', ['/site/logout'], ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                            )
                            ?>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
