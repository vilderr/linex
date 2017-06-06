<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\icons\Icon;
use linex\base\widgets\typehead\TypeHead;

/**
 * @var $this  \yii\web\View
 * @var $model \app\install\models\Last
 */
?>
<div class="installer">
    <?
    $form = ActiveForm::begin([
        'id'               => 'final-step',
        'validateOnSubmit' => true,
        'validateOnChange' => true,
        'validateOnType'   => true,
        'validationDelay'  => 300,
        'errorCssClass'    => 'error',
        'successCssClass'  => 'success',
    ]);
    ?>

    <?= $form->field($model, 'serverName'); ?>
    <?= $form->field($model, 'serverPort'); ?>

    <?= $form->field($model, 'cacheClass')->widget(TypeHead::className(), [
        'source'     => $model::getCacheClasses(),
        'limit'      => 10,
        'scrollable' => true,
    ]); ?>
    <?= $form->field($model, 'useMemcached', ['template' => '{input}{label}', 'options' => ['class' => 'form-group one-checkbox']])->checkbox(['class' => 'checkbox'], false) ?>
    <?= $form->field($model, 'keyPrefix') ?>

    <div class="controls">
        <?= Html::submitButton(Yii::t('install', 'Next') . ' ' . Icon::show('angle-right'), ['class' => 'btn btn-default btn-lg btn-flat']); ?>
    </div>

    <? ActiveForm::end(); ?>
</div>
