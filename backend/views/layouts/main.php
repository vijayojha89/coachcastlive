<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$settings = new common\models\Setting();
$settings = $settings->findOne(["status" => 1]);
if (Yii::$app->controller->action->id === 'login' || Yii::$app->controller->action->id === 'request-password-reset' || Yii::$app->controller->action->id === 'reset-password') {
    /**
     * Do not use this code in your template. Remove it. 
     * Instead, use the code  $this->layout = '//main-login'; in your controller.
     */
    echo $this->render(
            'main-login', ['content' => $content]
    );
} elseif (Yii::$app->controller->action->id === 'decreesdetail') {
    echo $this->render(
            'main-decrees', ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
        <head>
            <meta charset="<?= Yii::$app->charset ?>"/>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <?= Html::csrfMetaTags() ?>
            <title>FitMakersLive Admin Panel | <?= Html::encode($this->title) ?></title>
            <?php $this->head() ?>
            <link rel="shortcut icon" href='<?php echo Yii::$app->params['httpurl'] . 'setting_favicon_image/' . $settings->setting_favicon_image ?>' type="image/x-con" />
        </head>
        <body class="hold-transition skin-blue sidebar-mini">
            <?php $this->beginBody() ?>
            <div class="wrapper">

                <?=
                $this->render(
                        'header.php', ['directoryAsset' => $directoryAsset, "settings" => $settings]
                )
                ?>

                <?=
                $this->render(
                        'left.php', ['directoryAsset' => $directoryAsset]
                )
                ?>

                <?=
                $this->render(
                        'content.php', ['content' => $content, 'directoryAsset' => $directoryAsset]
                )
                ?>

            </div>

            <?php $this->endBody() ?>
        </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>

<script>
    $('.form-inline').hide();
    $('.lte-hide-title').hide();
    setTimeout("$('.alert').hide('slow');", 2000);
</script>