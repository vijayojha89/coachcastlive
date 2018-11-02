<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'),
        require(__DIR__ . '/../../common/config/params-local.php'),
        require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'name'=>'CoachCast Live',
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'class' => 'common\components\Request',
            'web' => '/frontend/web',
            'csrfParam' => '_csrf-frontend',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => FALSE,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logFile' => '@app/runtime/logs/eauth.log',
                    'categories' => ['nodge\eauth\*'],
                    'logVars' => [],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '272420033202654',
                    'clientSecret' => '728064ef1552ee1b145643a6d7aec9ee',
                    'scope' => 'email',
                ],
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => '244225652073-o2q2agr2li7879b0sdk78g21l4rhoffb.apps.googleusercontent.com',
                    'clientSecret' => 'XqlNrfVF471JUe1JmVGbx0qO',
                ],
            ],
        ],
        'eauth' => [
            'class' => 'nodge\eauth\EAuth',
            'popup' => true, // Use the popup window instead of redirecting.
            'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache' on production environments.
            'cacheExpire' => 1, // Cache lifetime. Defaults to 0 - means unlimited.
            'httpClient' => [
            // uncomment this to use streams in safe_mode
            //'useStreamsFallback' => true,
            ],
            'services' => [ // You can change the providers and their classes.                
                'facebook' => [
                    // register your app here: https://developers.facebook.com/apps/
                    'class' => 'nodge\eauth\services\FacebookOAuth2Service',
                    'clientId' => '375400386176608',
                    'clientSecret' => 'bfe43a63f6e586898e61ab809334d903',
                ],
                'google' => [
                    // register your app here: https://code.google.com/apis/console/
                    'class' => 'nodge\eauth\services\GoogleOAuth2Service',
                    'clientId' => '244225652073-o2q2agr2li7879b0sdk78g21l4rhoffb.apps.googleusercontent.com',
                    'clientSecret' => 'XqlNrfVF471JUe1JmVGbx0qO',
                    'title' => 'Google',
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'eauth' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@eauth/messages',
                ],
            ],
        ],
    /*
      'urlManager' => [
      'enablePrettyUrl' => true,
      'showScriptName' => false,
      'rules' => [
      ],
      ],
     */
    ],
    'params' => $params,
];
