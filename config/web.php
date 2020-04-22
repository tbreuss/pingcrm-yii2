<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'inertia'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'class' => 'tebe\inertia\web\Request',
            'cookieValidationKey' => '7d0d683457df8f6ff9d65e2b507c08cd'
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
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'login' => 'site/login',
                'logout' => 'site/logout',

                // Contacts
                'GET contacts' => 'contact/index',
                'POST contacts' => 'contact/insert',
                'GET contacts/create' => 'contact/create',
                'GET contacts/<id:\d+>/edit' => 'contact/edit',
                'PUT contacts/<id:\d+>' => 'contact/update',
                'DELETE contacts/<id:\d+>' => 'contact/delete',
                'PUT contacts/<id:\d+>/restore' => 'contact/restore',

                // Organizations
                'GET organizations' => 'organization/index',
                'POST organizations' => 'organization/insert',
                'GET organizations/create' => 'organization/create',
                'GET organizations/<id:\d+>/edit' => 'organization/edit',
                'PUT organizations/<id:\d+>' => 'organization/update',
                'DELETE organizations/<id:\d+>' => 'organization/delete',
                'PUT organizations/<id:\d+>/restore' => 'organization/restore',

                // Users
                'GET users' => 'user/index',
                'POST users' => 'user/insert',
                'GET users/create' => 'user/create',
                'GET users/<id:\d+>/edit' => 'user/edit',
                'PUT users/<id:\d+>' => 'user/update',
                'DELETE users/<id:\d+>' => 'user/delete',
                'PUT users/<id:\d+>/restore' => 'user/restore',

                'reports' => 'report/index',
                '500' => 'site/500'
            ]
        ],
        'inertia' => [
            'class' => 'tebe\inertia\Inertia',
            'assetsDirs' => [
                '@webroot/assets/inertia'
            ]
        ]
    ],
    'params' => $params,
];

if (false && YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
