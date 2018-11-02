<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>


    <?= $form->field($model, 'menu_name')->textInput(['maxlength' => true]) ?>
<div class="cl"></div>
<div class="image_setting">
        <div class="upload_logo_file">
            <i class="glyphicon glyphicon-open-file"></i>
            <p>File Upload</p>
        </div><?php $gnl = new \common\components\GeneralComponent(); ?><?= $form->field($model, 'menu_image')->fileInput() ?><img src="<?php echo $gnl->image_not_found('menu_image', $model->menu_image); ?>" style="background-color: #362f8c"/>
    </div><div class="image_setting">
        <div class="upload_logo_file">
            <i class="glyphicon glyphicon-open-file"></i>
            <p>File Upload</p>
        </div><?= $form->field($model, 'menu_background_image')->fileInput() ?><img src="<?php echo $gnl->image_not_found('menu_background_image', $model->menu_background_image); ?>" />
    </div>
				
				<div class="cl"></div><br />

				    <div class="form-group" id="button_update_delete">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>&nbsp;&nbsp;&nbsp;
        <?= Html::a(Yii::t('app', 'Cancel'), ['menu/index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
