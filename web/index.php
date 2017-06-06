<?php
// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
$installed = require(__DIR__ . '/../installed.php');

$c = $installed['installed'] === true ? __DIR__ . '/../config/web.php' : __DIR__ . '/../install/config.php';

$config = require($c);

(new yii\web\Application($config))->run();
