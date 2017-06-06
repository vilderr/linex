<?php
use yii\helpers\ArrayHelper;

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$basePath = dirname(__DIR__);
Yii::setAlias('@uploads', $basePath . DIRECTORY_SEPARATOR . 'web/upload');

$config = [
    'id'           => 'linex',
    'basePath'     => dirname(__DIR__),
    'defaultRoute' => 'base/default',
    'bootstrap'    => [
        'log',
        'linex\base\Bootstrap',
        'linex\base\modules\reference\Bootstrap',
        'users',
        'pages',
        'dashboard',
    ],
    'modules'      => [
        'dashboard' => [
            'class'   => 'linex\base\modules\dashboard\DashboardModule',
            'layout'  => 'main',
            'modules' => [
                'users'      => [
                    'class'               => 'linex\base\modules\user\UserModule',
                    'controllerNamespace' => 'linex\base\modules\user\controllers\backend',
                    'viewPath'            => '@vendor/linex/base/modules/user/views/backend',
                ],
                'pages'      => [
                    'class'               => 'linex\base\modules\pages\PagesModule',
                    'controllerNamespace' => 'linex\base\modules\pages\controllers\backend',
                    'viewPath'            => '@vendor/linex/base/modules/pages/views/backend',
                ],
                'reference' => [
                    'class'               => 'linex\base\modules\reference\ReferenceModule',
                    'controllerNamespace' => 'linex\base\modules\reference\controllers\backend',
                    'viewPath'            => '@vendor/linex/base/modules/reference/views/backend',
                ],
            ],
        ],
        'users'     => [
            'class' => 'linex\base\modules\user\UserModule',
        ],
        'pages'     => [
            'class' => 'linex\base\modules\pages\PagesModule',
        ],
        'dynagrid'  => [
            'class'           => '\kartik\dynagrid\Module',
            'dbSettings'      => [
                'tableName' => '{{%dynagrid}}',
            ],
            'dbSettingsDtl'   => [
                'tableName' => '{{%dynagrid_dtl}}',
            ],
            'dynaGridOptions' => [
                'storage'     => 'db',
                'gridOptions' => [
                    'toolbar' => [
                        '{dynagrid}',
                        '{toggleData}',
                        //'{export}',
                    ],
                    'export'  => false,

                ],
            ],

        ],
        'gridview'  => [
            'class' => '\kartik\grid\Module',
        ],
    ],
    'components'   => [
        'request'      => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'IuKWQKOeZ0P9gwYBfbfUGq95WySaTUaP',
        ],
        'authManager'  => [
            'class' => 'linex\base\components\CachedDbRbacManager',
            'cache' => 'cache',
        ],
        'user'         => [
            'class'           => '\yii\web\User',
            'identityClass'   => 'linex\base\modules\user\models\User',
            'enableAutoLogin' => true,
            'loginUrl'        => ['login'],
        ],
        'errorHandler' => [
            'errorAction' => 'default/error',
        ],
        'mailer'       => [
            'class'            => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 6 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class'          => 'yii\log\FileTarget',
                    'exportInterval' => 1,
                    'categories'     => ['info'],
                    'levels'         => ['info'],
                    'logFile'        => '@runtime/logs/info.log',
                    'logVars'        => [],
                ],
            ],
        ],
        'db'           => $db,
        'urlManager'   => [
            'enablePrettyUrl'     => true,
            'showScriptName'      => false,
            'enableStrictParsing' => true,
        ],
        'session'      => [
            'class'        => 'yii\web\DbSession',
            'timeout'      => 24 * 3600 * 30, // 30 days
            'useCookies'   => true,
            'cookieParams' => [
                'lifetime' => 24 * 3600 * 30,
            ],
        ],
    ],
    'params'       => $params,
];

$allConfig = ArrayHelper::merge(
    file_exists(__DIR__ . '/common-local.php') ? require(__DIR__ . '/common-local.php') : [],
    $config
);

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $allConfig['bootstrap'][] = 'debug';
    $allConfig['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $allConfig['bootstrap'][] = 'gii';
    $allConfig['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $allConfig;
