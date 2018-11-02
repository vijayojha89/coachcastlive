<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);

$settings = new common\models\Setting();
$settings = $settings->findOne(["status" => 1]);
?>
<?php
if (Yii::$app->request->get('incomplete') == 1) {
    $this->registerJs("
        
        $('#myModal').modal('toggle');
        history.pushState(null, null, BASE_URL)
    ", yii\web\View::POS_END);
}
?>  
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="no-js">
    <head>
        <meta charset="<?= Yii::$app->charset ?>" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="<?php echo Yii::$app->params['httpurl'] . 'setting_favicon_image/favicon.ico'?>" type="image/x-con" />
        <?= Html::csrfMetaTags() ?>
        <title><?= Yii::$app->name ?> | <?= Html::encode($this->title) ?></title>
        <script type="text/javascript">
            var BASE_URL = '<?= Url::base(true); ?>';
        </script>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div class="wrapper">
            <div id="preloader"></div>
            <?php echo $this->render('header.php', ['directoryAsset' => $directoryAsset, "settings" => $settings]); ?>
            <?php

            if (Yii::$app->session->hasFlash('success') || Yii::$app->session->hasFlash('error')) 
            {
                $this->registerJs('
                     $(".alert-success, .alert-danger").animate({opacity: 1.0}, 4000).fadeOut("slow");
                     $("ul.nav li.dropdown").hover(function() {
                            $(this).find(".dropdown-menu").stop(true, true).delay(100).fadeIn(200);
                            }, function() {
                            $(this).find(".dropdown-menu").stop(true, true).delay(100).fadeOut(200);
	                });
                ', \yii\web\VIEW::POS_READY);
            }
            ?>
            <?= Alert::widget() ?>
            
            <div>
                <?= $content ?>
            </div>

            <?= $this->render('footer.php', ['content' => $content, 'directoryAsset' => $directoryAsset,'settings'=>$settings]) ?>
            
        </div>
        <a href="#" class="scrollToTop"></a>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>