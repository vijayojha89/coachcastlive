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
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\BulkMail */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="bulk-mail-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'type')->dropDownList(['student' => 'Student', 'tutor' => 'Tutor'], ['prompt' => 'Select...', 'onchange' => 'usertype(this.value);']); ?>   
    <div class="cl"></div>
    <div class="form-group">
        <?php
        echo '<label class="control-label">Select Multiple User Or Tutor</label>';
        echo Select2::widget([
            'name' => 'BulkMail[user_id][]',
            'id' => 'bulkmail-user_id',
            'model' => $model,
            'data' => ArrayHelper::map($userdata, 'id', 'fullname'),
            'value' => (!$model->isNewRecord ? $result : ''),
            'options' => [
                'placeholder' => 'Select Users Or Tutors ...',
                'multiple' => true
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]);
        ?> 
    </div>
       <div class="cl"></div>
        <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>
 
   <div class="cl"></div>
    <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

    <div class="form-group" id="button_update_delete">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
<?= Html::a(Yii::t('app', 'Cancel'), ['bulk-mail/create'], ['class' => 'btn btn-danger']) ?>
    </div>

        <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs(
        '
             function usertype(val){
        $.ajax({
                url: "getusertype",
                data:"val="+val,
                success: function(result){
             //   alert(result);
                    $("#bulkmail-user_id").html(result);
                    
                    
            }});
          
            

  }', \yii\web\VIEW::POS_HEAD);
?>