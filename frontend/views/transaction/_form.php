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
/* @var $model common\models\Transaction */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
            .field-transaction-stripe_card_id{
                width:100%!important;
            }
            </style>  <div class="transaction-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'studypad_txn_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'question_id')->textInput() ?>

    <?= $form->field($model, 'stripe_charge_id')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'stripe_card_id')->widget(TinyMce::className(), [
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
        'toolbar1' => "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
      //  'toolbar2'=> "print preview media | forecolor backcolor emoticons",
         'image_advtab'=> true,
        'file_picker_callback'=> new yii\web\JsExpression("function(callback, value, meta) {
            
      if (meta.filetype == 'image') {
        $('#upload').trigger('click');
        $('#upload').on('change', function() {
          var file = this.files[0];
          var reader = new FileReader();
          reader.onload = function(e) {
            callback(e.target.result, {
              alt: ''
            });
          };
          reader.readAsDataURL(file);
        });
      }
    }"),
    
         
    ]
]);?>    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'amount_refunded')->textInput() ?>

    <?= $form->field($model, 'balance_transaction')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'captured')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stripe_customer_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'failure_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'failure_message')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paid')->textInput(['maxlength' => true]) ?>

    <div class="form-group" id="button_update_delete">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['transaction/index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
    