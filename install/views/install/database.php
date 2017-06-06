<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\icons\Icon;

/**
 * @var $this  \yii\web\View
 * @var $model \app\install\models\Config
 */
?>
<div class="installer">
    <?
    $form = ActiveForm::begin([
        'id'               => 'database-config',
        'validateOnSubmit' => true,
        'validateOnChange' => true,
        'validateOnType'   => true,
        'validationDelay'  => 300,
        'errorCssClass'    => 'error',
        'successCssClass'  => 'sucess',
        'fieldConfig'      => [
            'template' => "{label}{input}",
        ],
    ]);
    ?>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <?= $form->field($model, 'db_host')->textInput(['autocomplete' => 'off']); ?>
            <?= $form->field($model, 'db_name')->textInput(['autocomplete' => 'off']); ?>
            <?= $form->field($model, 'username')->textInput(['autocomplete' => 'off']); ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'enableSchemaCache', ['template' => '{input}{label}', 'options' => ['class' => 'form-group one-checkbox']])->checkbox(['class' => 'checkbox'], false); ?>
            <?= $form->field($model, 'schemaCacheDuration')->textInput(['autocomplete' => 'off']) ?>
            <?= $form->field($model, 'schemaCache')->textInput(['autocomplete' => 'off']) ?>
        </div>
    </div>

    <div class="controls">
        <?= Html::a(Icon::show('angle-left') . Yii::t('install', 'Back'), [Url::to(['install/requirements'])], ['class' => 'btn btn-default btn-lg btn-flat']); ?>
        <?= Html::submitButton(Icon::show('refresh') . ' ' . Yii::t('install', 'Check connection'), ['class' => 'btn btn-default btn-lg btn-flat', 'name' => 'check']); ?>
        <?= Html::submitButton(Yii::t('install', 'Next') . ' ' . Icon::show('angle-right'), ['class' => 'btn btn-default btn-lg btn-flat', 'name' => 'next']); ?>
    </div>
    <? ActiveForm::end(); ?>
</div>
