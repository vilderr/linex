<?php
return [
    'language' => 'ru',
    'components' => [
        'cache' => [
            'class' => 'yii\\caching\\FileCache',
            'keyPrefix' => 'lx',
        ],
    ],
    'modules' => [
        'base' => [
            'serverName' => 'linex-dev.local',
            'serverPort' => '80',
        ],
    ],
];