<?php

require_once 'Custom.php';

$params = require __DIR__ . '/params.php';

$config = [
    'id' => 'homei-13337',
    'name' => $params['title'],
    "language" => "id",
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => 'Asia/Jakarta',
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],
    ],
    'components' => [
        'reCaptcha' => [
            'class' => 'himiklab\yii2\recaptcha\ReCaptchaConfig',
            'siteKeyV3' => '6LebZLEaAAAAAAY17vA9mMiTFGXVKR6MwozfSRQY',
            'secretV3' => '6LebZLEaAAAAAEic2C6VgH1KTDrM4e44S4Ot15Me',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'h4nt000',
            'parsers' => [
                'multipart/form-data' => 'yii\web\MultipartFormDataParser',
            ],
        ],
        'response' => [
            'class' => '\yii\web\Response',
            'on beforeSend' => [
                \app\components\ErrorResponseHelper::class,
                "beforeResponseSend",
            ],
        ],
        'formatter' => [
            'class' => \app\formatter\CustomFormatter::class,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages', // if advanced application, set @frontend/messages
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        //'main' => 'main.php',
                    ],
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => $params['adminEmail.host'],
                'username' => $params['adminEmail.email'],
                'password' => $params['adminEmail.password'],
                'port' => $params['adminEmail.port'],
                'encryption' => $params['adminEmail.encryption'],
                'streamOptions' => [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ]
                ]
            ],
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Disable index.php
            'showScriptName' => false,
            // Disable r= routes
            'enablePrettyUrl' => true,
            'rules' => [
                // api route
                'api/v1/keuangan/<controller>/<action>' => 'api/v1/keuangan/<controller>/<action>',
                'api/v1/kontraktor/<controller>/<action>' => 'api/v1/kontraktor/<controller>/<action>',
                'api/v1/tukang/<controller>/<action>' => 'api/v1/tukang/<controller>/<action>',
                'api/tool/<controller>/<action>' => 'api/tool/<controller>/<action>',
                'api/v1/<controller>/<action>' => 'api/v1/<controller>/<action>',
                'api/<controller:\w+>/<action>' => 'api/<controller>/<action>',
                'api/<controller:\w+>/<action>/<id>' => 'api/<controller>/<action>',
                'POST api/<controller>' => 'api/<controller>/create',
                'PUT,PATCH api/<controller>/<id>' => 'api/<controller>/update',
                'GET,HEAD api/<controller>' => 'api/<controller>/index',
                'GET,HEAD api/<controller>/<id>' => 'api/<controller>/view',

                // web route
                'home/pages/<id:[\w\-\_]+>' => 'home/pages',
                'home/bahan-material' => 'home/bahan-material/index',
                'home/konsultasi/<ticket:[\w\-\_]+>' => 'home/konsultasi',
                'konsultasi/chat/<ticket:[\w\-\_]+>' => 'konsultasi/chat',

                '<controller:[\w\-\_]+>' => '<controller>/index',
                '<controller:[\w\-\_]+>/<action:[\w\-\_]+>/<id:\d+>' => '<controller>/<action>',
                '<controller:[\w\-\_]+>/<action:[\w\-\_]+>' => '<controller>/<action>',
            ],
        ],

        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],

            ],
        ],
        'db' => require __DIR__ . '/db.php',
    ],
    'params' => $params,
    'defaultRoute' => "home",
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'giiant-model' => [
                'class' => 'app\template\giiant\generators\model\Generator',
                'templates' => [
                    'Annex' => '@app/template/giiant/generators/model/default',
                ],
            ],
            'giiant-crud' => [
                'class' => 'app\template\giiant\generators\crud\Generator',
                'templates' => [
                    'Annex' => '@app/template/giiant/generators/crud/default',
                ],
            ],
        ],
    ];
}

return $config;
