<?php

namespace app\install\models;

use Yii;
use yii\base\Model;

/**
 * Class User
 * @package app\install\models
 */
class User extends Model
{
    public $username = 'admin';
    public $password = '';
    public $email = '';
    public $first_name;
    public $last_name;

    public function rules()
    {
        return [
            [['email'], 'email'],
            [['email', 'username', 'password', 'first_name', 'last_name'], 'filter', 'filter' => 'trim'],
            [['email', 'username', 'password'], 'required'],
            ['password', 'string', 'min' => 8],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email'      => Yii::t('install', 'email'),
            'username'   => Yii::t('install', 'username'),
            'password'   => Yii::t('install', 'password'),
            'first_name' => Yii::t('install', 'first_name'),
            'last_name'  => Yii::t('install', 'last_name'),
        ];
    }
}