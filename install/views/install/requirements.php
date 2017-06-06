<?php
use yii\helpers\Html;
use kartik\icons\Icon;

/**
 * @var $this       \yii\web\View
 * @var $phpVersion bool
 */

//echo '<pre>'; print_r(Yii::$app->id); echo '</pre>';
?>
<div class="installer">
    <div class="row demand">
        <div class="col-sm-9"><?= Yii::t('install', 'PHP version') ?></div>
        <div class="col-sm-3"><span class="<?= $phpVersion ? 'ok' : 'text-danger' ?>"><?= PHP_VERSION; ?></span></div>
    </div>
    <div class="controls">
        <?= Html::a(Yii::t('install', 'Next') . ' ' . Icon::show('angle-right'), ['database'], ['class' => 'btn btn-default btn-lg btn-flat']); ?>
    </div>
</div>
