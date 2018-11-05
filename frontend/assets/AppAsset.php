<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
  
    public $css = [
        'css/bootstrap.min.css',
        'css/font-awesome.min.css',
        'css/owl.carousel.min.css',
        'css/owl.theme.default.min.css',
        'css/meanmenu.min.css',
        'css/nivo-slider.css',
        'css/preview.css',
        'css/flaticon.css',
        'css/animate.css',
        'css/hover-min.css',
        'css/style.css',
        'css/responsive.css',
    ];

    public $js = [
        'js/bootstrap.min.js',
        'js/bootstrap-tabcollapse.js',
        'js/jquery.meanmenu.min.js',
        'js/owl.carousel.min.js',
        'js/jquery.nivo.slider.js',
        'js/home.js',
        'js/wow.min.js',
        'js/main.js',
        'js/modernizr-2.8.3.min.js',
    ];
}
