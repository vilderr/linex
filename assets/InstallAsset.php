<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class InstallAsset
 * @package app\assets
 */
class InstallAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/install.css',
    ];

    public $js = [];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        '\kartik\icons\FontAwesomeAsset',
    ];
}