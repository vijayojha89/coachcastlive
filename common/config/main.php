
<?php

error_reporting(0);
define('PAGE_SIZE', 10);
define('CHAT_PAGE_SIZE', 10);
define('SERVICE_TOKEN_EXPIRE_MSG', 'Session is expired.');
define("IOS_PUSHNOTIFICATION_URL", "ssl://gateway.push.apple.com:2195");
define("ANDROID_PUSHNOTIFICATION_URL", 'https://fcm.googleapis.com/fcm/send');

return [
    'name' => 'CoachCast Live',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone'=>'UTC',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'authTimeout' => 86400,
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Disable index.php
            'showScriptName' => false,
            // Disable r= routes
            'enablePrettyUrl' => true,
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ],
    ],
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module',
            // enter optional module parameters below - only if you need to  
            // use your own export download action or custom translation 
            // message source
            // 'downloadAction' => 'gridview/export/download',
            'i18n' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@kvgrid/messages',
                'forceTranslation' => true
            ]
        ]
    ]
];
