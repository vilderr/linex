<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\icons\Icon;

/**
 * @var $this   \yii\web\View
 * @var $model  \app\install\models\Migration
 */
?>
<div class="installer">
    <? $form = ActiveForm::begin([
        'id' => 'migrate-create',
    ]); ?>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <?= $form->field($model, 'install_demo_data', ['template' => '{input}{label}', 'options' => ['class' => 'form-group one-checkbox']])->checkbox(['class' => 'checkbox'], false); ?>
        </div>
    </div>
    <div class="controls">
        <?= Html::a(Icon::show('angle-left') . Yii::t('install', 'Back'), ['database'], ['class' => 'btn btn-default btn-lg btn-flat']); ?>
        <?= Html::submitButton(Yii::t('install', 'Next') . ' ' . Icon::show('angle-right'), ['class' => 'btn btn-default btn-lg btn-flat']); ?>
    </div>

    <? ActiveForm::end(); ?>
</div>
