<?php

namespace app\install\models;

use Yii;
use yii\base\Model;

/**
 * Class Migration
 * @package app\install\models
 */
class Migration extends Model
{
    public $install_demo_data = false;

    public function rules()
    {
        return [
            [['install_demo_data'], 'filter', 'filter' => 'boolval'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'ignore_time_limit_warning' => Yii::t('install', 'Ignore time limit warning'),
            'install_demo_data'         => Yii::t('install', 'Install demo data'),
        ];
    }
}