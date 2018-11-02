<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="container">
  <div class="left-right-content">
    <div class="col-md-12">
      <div class="row">
        <div class="cl"></div>
      </div>
    </div>
  </div>
  <div class="cl"></div>
  <div class="founder-form">
   
   <div class="row">
    <?php
        $form = \yii\widgets\ActiveForm::begin([
                    'id' => 'db-form',
                    'class'=>'dbform'
        ]);
        ?>
     <div class="col-md-6">   
    <?= $form->field($model, 'prj_name')->textInput(['maxlength' => 50])->label('Project Name') ?>
    </div><div class="cl"></div>
    <div class="col-md-6">
    <?= $form->field($model, 'prj_description')->textarea(['maxlength' => 50])->label('Project Description') ?>
    </div><div class="cl"></div>
     <div class="col-md-6">   
    <?= $form->field($model, 'prj_email')->textInput(['maxlength' => 50])->label('Email') ?>
     </div><div class="cl"></div><br /><br />
     
    <div class="image_setting">
        <div class="upload_logo_file">
            <!--<p>File Upload</p>-->
        </div><?php $gnl = new \common\components\GeneralComponent(); ?><?= $form->field($model, 'prj_logo')->fileInput() ?>
        <!--<img id="preview_logo" src="<?php echo $gnl->image_not_found('prj_logo', $model->prj_logo); ?>" />-->
    </div><div class="cl"></div><br /><br />
    
    <div class="image_setting">
        <div class="upload_logo_file">
            <!--<p>File Upload</p>-->
        </div><?php $gnl = new \common\components\GeneralComponent(); ?><?= $form->field($model, 'prj_fevicon_logo')->fileInput() ?>
        <!--<img id="preview_fevicon" src="<?php echo $gnl->image_not_found('prj_fevicon_logo', $model->prj_fevicon_logo); ?>" />-->
    </div><div class="cl"></div>
    <br /><br />
    <div class="col-md-12 btnbox">
    				<?= yii\helpers\Html::submitButton('Submit', ['class' => 'btn btn-primary submitbtn', 'name' => 'db-button']) ?>
    </div>
				<?php \yii\widgets\ActiveForm::end(); ?>
  </div>
  </div>
</div>

    <script>
                    var URL = window.URL || window.webkitURL;

                    var input_0 = document.querySelector('#dynamicmodel-prj_logo');
                    var preview_0 = document.querySelector('#preview_logo');

                    input_0.addEventListener('change', function () {
                        preview_0.src = URL.createObjectURL(this.files[0]);
                    });

                    preview_0.addEventListener('load', function () {
                        URL.revokeObjectURL(this.src);

                    });
                    </script><script>
                    var URL = window.URL || window.webkitURL;

                    var input_1 = document.querySelector('#dynamicmodel-prj_fevicon_logo');
                    var preview_1 = document.querySelector('#preview_fevicon');

                    input_1.addEventListener('change', function () {
                        preview_1.src = URL.createObjectURL(this.files[0]);
                    });

                    preview_1.addEventListener('load', function () {
                        URL.revokeObjectURL(this.src);

                    });
                    </script><script>

