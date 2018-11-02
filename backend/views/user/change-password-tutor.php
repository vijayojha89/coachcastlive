<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\User $model
 */
$this->title = Yii::t('app', 'Change password');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Change password');
$model->password_hash = '';
?>




<div class="user-form">
    <div class="panel panel-default">
        <div class="panel-body">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'user',
            ]);
            ?>
            <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>

            <?= $form->field($model, 'confirm_password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>

<div class="cl"></div>
            <div class="form-group" id="button_update_delete">

                <?php if ($model->isNewRecord): ?>
                    <?=
                    Html::submitButton(
                            '<span class="glyphicon glyphicon-plus-sign"></span> ' . Yii::t('app', 'Create'), ['class' => 'btn btn-success']
                    )
                    ?>
                <?php else: ?>
                    <?=
                    Html::submitButton(
                            '<span class="glyphicon glyphicon-ok"></span> ' . Yii::t('app', 'Save'), ['class' => 'btn btn-primary']
                    )
                    ?>
                <?php endif; ?>
                &nbsp;&nbsp;&nbsp;
                <?php if(Yii::$app->user->id == \common\components\GeneralComponent::decrypt($_REQUEST['id'])){?>
		<?= Html::a(Yii::t('app', 'Cancel'), ['/'], ['class' => 'btn btn-danger']) ?>
                <?php }else{?>
		<?= Html::a(Yii::t('app', 'Cancel'), ['tutor'], ['class' => 'btn btn-danger']) ?>
                <?php }?>

            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

