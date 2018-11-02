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
/* @var $model common\models\Career */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
            .field-career-description{
                width:100%!important;
            }
            </style> 
<div class="career-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'department')->dropDownList(Yii::$app->params['career_category'], ['prompt' => 'Select...']); ?>    

    <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>
<?= $form->field($model,'description')->widget(TinyMce::className(), [
    'options' => ['rows' => 6],
    'clientOptions' => [
        'plugins' => [
      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table contextmenu directionality",
      "emoticons template paste textcolor colorpicker textpattern"
        ],
                        'menubar'=>FALSE,

        'paste_data_images'=> true,
        'toolbar'=> 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fontselect fontsizeselect styleselect forecolor backcolor | link unlink anchor | image | preview code',
      //  'toolbar2'=> "print preview media | forecolor backcolor emoticons",
         'image_advtab'=> true,
         
    ]
]);?>
    <div class="form-group" id="button_update_delete">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['career/index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
