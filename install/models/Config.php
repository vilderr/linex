<?php

namespace app\install\models;

use app\install\components\InstallHelper;
use Yii;
use yii\base\Model;

/**
 * Class Config
 * @package app\install\models
 */
class Config extends Model
{
    public $db_host = 'localhost';
    public $db_name = 'linex';
    public $username = 'root';
    public $password = '';
    public $enableSchemaCache = true;
    public $schemaCacheDuration = 86400;
    public $schemaCache = 'cache';

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['db_host', 'db_name', 'username', 'schemaCache'], 'required'],
            [['password'], 'string'],
            [['enableSchemaCache'], 'filter', 'filter' => 'boolval'],
            [['enableSchemaCache'], 'boolean'],
            [['schemaCacheDuration'], 'integer'],
            [['schemaCacheDuration'], 'filter', 'filter' => 'intval'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'db_host'             => Yii::t('install', 'db_host'),
            'db_name'             => Yii::t('install', 'db_name'),
            'username'            => Yii::t('install', 'username'),
            'password'            => Yii::t('install', 'password'),
            'enableSchemaCache'   => Yii::t('install', 'enableSchemaCache'),
            'schemaCacheDuration' => Yii::t('install', 'schemaCacheDuration'),
            'schemaCache'         => Yii::t('install', 'schemaCache'),
        ];
    }

    public function testConnection()
    {
        $result = false;
        $config = InstallHelper::createDatabaseConfig($this->getAttributes());

        try {
            /** @var \yii\db\Connection $dbComponent */
            $dbComponent = Yii::createObject(
                $config
            );

            $dbComponent->open();
            $result = true;
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('danger', Yii::t('install', 'Connection error: {message}', [
                'message' => $e->getMessage(),
            ]));
        }

        return $result;
    }
}