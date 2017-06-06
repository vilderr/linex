<?php
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this \yii\web\View
 */
?>
<div class="installer text-center">
    <?= Html::a(
        Yii::t('install', 'Open site frontend'),
        Url::to(['complete', 'redirect' => 'frontend']),
        [
            'class'       => 'btn btn-default btn-lg btn-flat',
            'data-method' => 'post',
        ]
    ) ?>
    &nbsp;
    <?= Html::a(
        Yii::t('install', 'Open backend'),
        Url::to(['complete', 'redirect' => 'backend']),
        [
            'class'       => 'btn btn-default btn-lg btn-flat',
            'data-method' => 'post',
        ]
    ) ?>
</div>
