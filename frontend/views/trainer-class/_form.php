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
/* @var $model frontend\models\TrainerClass */
/* @var $form yii\widgets\ActiveForm */
$gnl = new \common\components\GeneralComponent();
?>
<div class="trainer-class-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div  class="row">
        <div  class="col-sm-6">
     <?php
                                                                echo $form->field($model, 'workout_type_id')
                                                                        ->dropDownList(ArrayHelper::map(\common\models\WorkoutType::find()
                                                                                        ->where(['status' => 1])->asArray()->all(), 'workout_type_id', 'name'), ['prompt' => '']);
                                                                ?>
    
    
    </div>
        <div  class="col-sm-6">
    <?= $form->field($model, 'price')->textInput() ?>
</div>
</div>
     
    <?php // $form->field($model, 'start_date')->textInput() ?>
         
    <?php // $form->field($model, 'end_date')->textInput() ?>
       
    <?php // $form->field($model, 'time')->textInput() ?>
        
    <div  class="row">
    <div  class="col-sm-6">
    <?php 
    echo '<label class="control-label">Start / End Dates</label>';
echo DatePicker::widget([
    'name' => 'TrainerClass[start_date]',
    'value' => $model->start_date,
    'type' => DatePicker::TYPE_RANGE,
    'name2' => 'TrainerClass[end_date]',
    'value2' => $model->end_date,
    'pluginOptions' => [
        'autoclose'=>true,
        'format' => 'yyyy-mm-dd'
    ]
]);
?>
    </div>
    <div  class="col-sm-6">
    <?php echo $form->field($model, 'time')->widget(TimePicker::classname(), []); ?>
    
    </div>
    </div>
    <div  class="row">
        <div  class="col-sm-2">
    <?= $form->field($model, 'class_image')->fileInput() ?>
        </div>
         <div  class="col-sm-3">
             <img id="preview_class_image_0" class="img-border" src="<?php echo $gnl->image_not_found_hb( $model->class_image,'class_image',1); ?>" />
     </div>
         </div>
    <div class="form-group pro-bottom-btn" id="button_update_delete">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['trainer-class/index'], ['class' => 'btn btn-dark']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    
    
</div>
    <script>
                    var URL = window.URL || window.webkitURL;

                    var input_0 = document.querySelector('#trainerclass-class_image');
                    var preview_0 = document.querySelector('#preview_class_image_0');

                    input_0.addEventListener('change', function () {
                        preview_0.src = URL.createObjectURL(this.files[0]);
                    });

                    preview_0.addEventListener('load', function () {
                        URL.revokeObjectURL(this.src);

                    });
                    </script>