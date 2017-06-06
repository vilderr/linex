<?php

namespace app\install\models;

use Yii;
use yii\base\Model;
use linex\base\validators\ClassNameValidator;

/**
 * Class Last
 * @package app\modules\install\models
 */
class Last extends Model
{
    public $serverName = 'localhost';
    public $serverPort = 80;
    public $cacheClass = 'yii\caching\FileCache';
    public $useMemcached = false;
    public $keyPrefix = 'lx';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['serverName', 'serverPort', 'cacheClass', 'keyPrefix'], 'filter', 'filter' => 'trim'],
            [['serverName', 'cacheClass'], 'required'],
            [['useMemcached'], 'filter', 'filter' => 'boolval'],
            [['useMemcached'], 'boolean'],
            [['cacheClass'], ClassNameValidator::className()],
        ];
    }

    /**
     * @return array
     */
    public static function getCacheClasses()
    {
        return [
            'yii\caching\FileCache',
            'yii\caching\MemCache',
            'yii\caching\XCache',
            'yii\caching\ZendDataCache',
            'yii\caching\ApcCache',
        ];
    }

    public function attributeLabels()
    {
        return [
            'serverName' => Yii::t('install', 'Server Name'),
            'serverPort' => Yii::t('install', 'Server Port'),
            'cacheClass' => Yii::t('install', 'Cache Class'),
            'keyPrefix'  => Yii::t('install', 'Key Prefix'),
        ];
    }
}