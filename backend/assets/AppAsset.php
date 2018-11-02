<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */


class AppAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
       'css/site.css',
       'css/fullcalendar/fullcalendar.css',
    ];
    public $js = [
        'js/fullcalendar/lib/moment.min.js',
        'js/fullcalendar/fullcalendar.min.js',
        'js/fullcalendar/lang-all.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}