<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\icons\Icon;

/**
 * @var $this  \yii\web\View
 * @var $model \app\install\models\User
 */
?>
<div class="installer">
    <? $form = ActiveForm::begin([
        'id'               => 'user-create',
        'validateOnSubmit' => true,
        'validateOnChange' => true,
        'validateOnType'   => true,
        'validationDelay'  => 300,
        'errorCssClass'    => 'error',
        'successCssClass'  => 'sucess',
        'fieldConfig'      => [
            'template' => "{label}{input}",
        ],
    ]); ?>

    <?= $form->field($model, 'first_name')->textInput(['autocomplete' => 'off']); ?>
    <?= $form->field($model, 'last_name')->textInput(['autocomplete' => 'off']); ?>
    <?= $form->field($model, 'username')->textInput(['autocomplete' => 'off']); ?>
    <?= $form->field($model, 'password')->passwordInput(); ?>
    <?= $form->field($model, 'email')->textInput(['autocomplete' => 'off']); ?>

    <div class="controls">
        <?= Html::submitButton(Yii::t('install', 'Next') . ' ' . Icon::show('angle-right'), ['class' => 'btn btn-default btn-lg btn-flat']); ?>
    </div>

    <? ActiveForm::end(); ?>

    <div class="small info-text"><?= Yii::t('install', 'Password must consist of Latin letters and numbers. The length is not less than 8 characters.') ?></div>
</div>
