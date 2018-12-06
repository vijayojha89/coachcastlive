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
/* @var $model app\models\TrainerVideo */
/* @var $form yii\widgets\ActiveForm */
$gnl = new \common\components\GeneralComponent();
?>
<div class="trainer-video-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="row">
    <div class="col-sm-6">
     <?php
                                                                echo $form->field($model, 'workout_type_id')
                                                                        ->dropDownList(ArrayHelper::map(\common\models\WorkoutType::find()
                                                                                        ->where(['status' => 1])->asArray()->all(), 'workout_type_id', 'name'), ['prompt' => '']);
                                                                ?>
    
    
     </div>
 <div class="col-sm-6">
    <?= $form->field($model, 'price')->textInput() ?>
      </div>
</div>
    
    <div class="row">


        <div  class="col-sm-6">
        <div class="img_users">
                                           
         <?php echo $form->field($model, 'video_image', [
         'template' => '{label}<span class="fileupload">Upload video images</span> {input}{error}{hint}'
         ])->fileInput(['autofocus' => true,'value'=>'','class'=>'form-control']) ;
         ?>
         <img id="preview_video_image_0" class="img-border  upload-preview" src="<?php echo $gnl->image_not_found_hb( $model->video_image,'video_image',1); ?>" />
        </div>
        </div>

        <div  class="col-sm-12">
        <div class="img_users">
                                           
         <?php echo $form->field($model, 'video_file', [
         'template' => '{label}<span class="fileupload">Upload video</span> {input}{error}{hint}'
         ])->fileInput(['autofocus' => true,'value'=>'','class'=>'form-control']) ;
         ?>
         
         <?php if (!$model->isNewRecord && !empty($model->video_file) ) { ?>
                    <div class="videosource">
                    <video controls>
                        <source src="<?php echo $gnl->video_not_found_hb( $model->video_file,'video_file'); ?>" type="video/mp4">
                    </video>
                    </div>
        <?php } ?>
        </div>
        </div>
       
         </div>



       
     <!-- <div class="col-sm-2">   
    <?= $form->field($model, 'video_file')->fileInput() ?>
     </div>
        <div class="col-sm-12">
     <?php if (!$model->isNewRecord && !empty($model->video_file) ) { ?>
                    <div class="videosource">
                    <video controls>
                        <source src="<?php echo $gnl->video_not_found_hb( $model->video_file,'video_file'); ?>" type="video/mp4">
                    </video>
                    </div>
        <?php } ?>
        </div> -->
    </div>
    <div class="form-group pro-bun-wrap pro-bottom-btn" id="button_update_delete">
        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn' : 'btn']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['trainer-video/index'], ['class' => 'btn btn-dark']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
    <script>
                    var URL = window.URL || window.webkitURL;

                    var input_0 = document.querySelector('#trainervideo-video_image');
                    var preview_0 = document.querySelector('#preview_video_image_0');

                    input_0.addEventListener('change', function () {
                        preview_0.src = URL.createObjectURL(this.files[0]);
                    });

                    preview_0.addEventListener('load', function () {
                        URL.revokeObjectURL(this.src);

                    });
                    </script>