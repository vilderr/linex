<?php
$config = [
    'id'                  => 'Linex',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => [
        'log',
    ],
    'language'            => 'ru',
    'viewPath'            => '@app/install/views',
    'controllerNamespace' => 'app\\install\\controllers',
    'defaultRoute'        => 'install',
    'components'          => [
        'cache'        => [
            'class' => 'yii\caching\DummyCache',
        ],
        'user'         => [
            'identityClass'   => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 6 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'request'      => [
            'cookieValidationKey'  => 'LINEX_TEMP_COOKIE',
            'enableCsrfValidation' => true,
        ],
        'assetManager' => [
            'class'     => 'yii\web\AssetManager',
            'forceCopy' => true,
        ],
        'i18n'         => [
            'translations' => [
                'install' => [
                    'class'            => 'yii\i18n\PhpMessageSource',
                    'forceTranslation' => true,
                ],
            ],
        ],
        'urlManager'   => [
            'enablePrettyUrl'     => true,
            'showScriptName'      => false,
            'enableStrictParsing' => true,
            'rules'               => [
                ''                 => 'install/index',
                'install'          => 'install/requirements',
                'install/database' => 'install/database',
                'install/migrate'  => 'install/migrate',
                'install/user'     => 'install/user',
                'install/last'     => 'install/last',
                'install/complete' => 'install/complete',
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'install/error',
        ],
    ],
    'params'              => [
        'icon-framework' => 'fa',

    ],
];

if (YII_ENV_DEV) {
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