<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=coachcastlive',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
//        'mailer' => [
//            'class' => 'yii\swiftmailer\Mailer',
//            'viewPath' => '@common/mail',
//            'useFileTransport' => true,
//        ],
        
         'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        'useFileTransport' => false,
        'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.gmail.com',  
            'username' => 'coachcastlive@gmail.com',
            'password' => 'john@1234',
            'port' => '587', 
            'encryption' => 'tls', 
        ],
    ]
    ],
];
