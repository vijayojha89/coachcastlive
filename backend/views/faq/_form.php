<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\time\TimePicker;
use dosamigos\tinymce\TinyMce;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model common\models\Faq */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
            .field-faq-content{
                width:100%!important;
            }
            </style> 
<div class="faq-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<?php
$this->registerJsFile(yii\helpers\Url::base() . '/js/tinymce/tinymce.min.js', [yii\web\JqueryAsset::className()]);
$this->registerJs(" 
 tinymce.init({
    selector: '#faq-content',
    plugins: [
         'advlist autolink link image lists charmap print preview hr anchor pagebreak',
         'searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking',
         'table contextmenu directionality emoticons paste textcolor filemanager'
    ],
    image_advtab: true,
    toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fontselect fontsizeselect styleselect forecolor backcolor | link unlink anchor | image | print preview code',
  });       
");
?> 
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <div class="cl"></div>
    <?= $form->field($model, 'content')->textarea() ?>

    <div class="form-group" id="button_update_delete">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['faq/index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
    