<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SettingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="setting-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'setting_id') ?>

    <?= $form->field($model, 'setting_google') ?>

    <?php // echo $form->field($model, 'setting_facebook') ?>

    <?php // echo $form->field($model, 'setting_linkedin') ?>

    <?php // echo $form->field($model, 'setting_yahoo') ?>

    <?php // echo $form->field($model, 'setting_logo_image') ?>

    <?php // echo $form->field($model, 'setting_favicon_image') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_date') ?>

    <?php // echo $form->field($model, 'modified_by') ?>

    <?php // echo $form->field($model, 'modified_date') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
